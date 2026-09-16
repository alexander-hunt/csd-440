#!/usr/bin/env python3
"""Create the CSD-440 Module 8 database and its limited student account."""

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
		description="Create the Module 8 database and configure its student account."
	)
	parser.add_argument(
		"--mysql-client",
		type=Path,
		help="Path to mysql.exe. By default, a local MySQL Server client is used when available.",
	)
	return parser.parse_args()


def main() -> int:
	arguments = get_arguments()
	root_password = os.environ.get("MYSQL_ROOT_PASSWORD")
	mysql_client = find_mysql_client(arguments.mysql_client)

	if not root_password:
		print("MYSQL_ROOT_PASSWORD is not set. Set it before running this script.", file=sys.stderr)
		return 1

	if mysql_client is None:
		print("MySQL client was not found. Supply its path with --mysql-client.", file=sys.stderr)
		return 1

	sql = f"""
CREATE DATABASE IF NOT EXISTS `{DATABASE_NAME}`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '{STUDENT_USER}'@'localhost' IDENTIFIED BY '{STUDENT_PASSWORD}';
ALTER USER '{STUDENT_USER}'@'localhost' IDENTIFIED BY '{STUDENT_PASSWORD}';
GRANT ALL PRIVILEGES ON `{DATABASE_NAME}`.* TO '{STUDENT_USER}'@'localhost';
FLUSH PRIVILEGES;
"""

	child_environment = os.environ.copy()
	child_environment["MYSQL_PWD"] = root_password

	try:
		result = subprocess.run(
			[
				str(mysql_client),
				"--protocol=TCP",
				"--host=localhost",
				"--user=root",
				"--execute",
				sql,
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
		print(f"Database setup failed: {detail}", file=sys.stderr)
		return result.returncode

	print(f"Database '{DATABASE_NAME}' is ready.")
	print(f"Account '{STUDENT_USER}'@'localhost' can access only '{DATABASE_NAME}'.")
	return 0


if __name__ == "__main__":
	raise SystemExit(main())