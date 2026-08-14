#!/usr/bin/env python3

from __future__ import annotations

import csv
import re
import sys
import xml.etree.ElementTree as ET
import zipfile
from pathlib import Path


NS = {"a": "http://schemas.openxmlformats.org/spreadsheetml/2006/main"}


def normalize_name(value: str) -> str:
    value = value.strip().lower()
    value = re.sub(r"[^a-z0-9]+", " ", value)
    return re.sub(r"\s+", " ", value).strip()


def load_shared_strings(zf: zipfile.ZipFile) -> list[str]:
    root = ET.fromstring(zf.read("xl/sharedStrings.xml"))
    items: list[str] = []
    for si in root.findall("a:si", NS):
        texts = [node.text or "" for node in si.iterfind(".//a:t", NS)]
        items.append("".join(texts).strip())
    return items


def read_sheet_rows(zf: zipfile.ZipFile, sheet_path: str, shared_strings: list[str]) -> list[dict[str, str]]:
    root = ET.fromstring(zf.read(sheet_path))
    rows: list[dict[str, str]] = []

    for row in root.findall(".//a:sheetData/a:row", NS):
        values: dict[str, str] = {}
        for cell in row.findall("a:c", NS):
            ref = cell.attrib.get("r", "")
            match = re.match(r"[A-Z]+", ref)
            if not match:
                continue
            col = match.group(0)
            cell_type = cell.attrib.get("t", "")
            value = ""
            raw = cell.find("a:v", NS)
            if raw is not None:
                text = raw.text or ""
                if cell_type == "s" and text.isdigit():
                    idx = int(text)
                    value = shared_strings[idx] if 0 <= idx < len(shared_strings) else text
                else:
                    value = text
            else:
                inline = cell.find("a:is/a:t", NS)
                if inline is not None:
                    value = inline.text or ""
            values[col] = value.strip()
        rows.append(values)
    return rows


def extract_map(rows: list[dict[str, str]]) -> dict[str, dict[str, str]]:
    result: dict[str, dict[str, str]] = {}
    current_name = ""

    for cells in rows:
        col_a = cells.get("A", "").strip()
        col_b = cells.get("B", "").strip()
        col_c = cells.get("C", "").strip()

        if col_a.isdigit() and col_b and col_b != "2":
            current_name = col_b
            continue

        if not current_name:
            continue

        if not col_a and col_c and col_c not in {"3", "PKT/NRP", "NRP/NIP", "TMT"}:
            result[normalize_name(current_name)] = {
                "nama": current_name,
                "nip": col_c,
            }
            current_name = ""

    return result


def main() -> int:
    if len(sys.argv) != 3:
        print("Usage: extract_nip_from_xlsx.py <input.xlsx> <output.csv>", file=sys.stderr)
        return 1

    input_path = Path(sys.argv[1])
    output_path = Path(sys.argv[2])

    if not input_path.exists():
        print(f"File tidak ditemukan: {input_path}", file=sys.stderr)
        return 1

    with zipfile.ZipFile(input_path) as zf:
        shared_strings = load_shared_strings(zf)
        data = {}
        for sheet in ("xl/worksheets/sheet1.xml", "xl/worksheets/sheet2.xml"):
            data.update(extract_map(read_sheet_rows(zf, sheet, shared_strings)))

    with output_path.open("w", newline="", encoding="utf-8") as fh:
        writer = csv.writer(fh)
        writer.writerow(["nama", "nip"])
        for item in data.values():
            writer.writerow([item["nama"], item["nip"]])

    print(f"Berhasil ekstrak {len(data)} data NIP ke {output_path}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
