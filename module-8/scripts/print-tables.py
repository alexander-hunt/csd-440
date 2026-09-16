#!/usr/bin/env python3
"""Print the tables available in the CSD-440 Module 8 database."""

from __future__ import annotations

import argparse
import os
from pathlib import Path
import subprocess
import sys


DATABASE_NAME = "baseball_01"
STUDENT_USER = "student1"
STUDENT_PASSWORD = "pass"


def find_mysql_client(requested_client: Path | None) -> Path | None:
	if requested_client is not None:
		return requested_client

	program_files = Path(os.environ.get("ProgramFiles", r"C:\Program Files"))
	server_clients = sorted(
		(program_files / "MySQL").glob("MySQL Server */bin/mysql.exe"),
		reverse=True,
	)
	for client in server_clients:
		if client.is_file():
			return client

	xampp_client = Path(r"C:\xampp\mysql\bin\mysql.exe")
	return xampp_client if xampp_client.is_file() else None


def get_arguments() -> argparse.Namespace:
	parser = argparse.ArgumentParser(
		description="Print all tables in the Module 8 database."
	)
	parser.add_argument(
		"--mysql-client",
		type=Path,
		help="Path to mysql.exe. By default, a local MySQL Server client is used when available.",
	)
	return parser.parse_args()


def main() -> int:
	arguments = get_arguments()
	mysql_client = find_mysql_client(arguments.mysql_client)

	if mysql_client is None:
		print("MySQL client was not found. Supply its path with --mysql-client.", file=sys.stderr)
		return 1

	child_environment = os.environ.copy()
	child_environment["MYSQL_PWD"] = STUDENT_PASSWORD

	try:
		result = subprocess.run(
			[
				str(mysql_client),
				"--protocol=TCP",
				"--host=localhost",
				f"--user={STUDENT_USER}",
				f"--database={DATABASE_NAME}",
				"--batch",
				"--skip-column-names",
				"--execute=SHOW TABLES",
			],
			env=child_environment,
			capture_output=True,
			text=True,
			check=False,
		)
	except OSError as exception:
		print(f"Unable to run the MySQL client: {exception}", file=sys.stderr)
		return 1

	if result.returncode != 0:
		detail = result.stderr.strip() or "MySQL returned an unspecified error."
		print(f"Unable to list tables: {detail}", file=sys.stderr)
		return result.returncode

	tables = result.stdout.strip()
	if tables:
		print(tables)
	else:
		print("No tables were found in baseball_01.")
	return 0


if __name__ == "__main__":
	raise SystemExit(main())