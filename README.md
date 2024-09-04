# Flash Electronics API

This repository contains the files required to run a RESTful API in a linux-based server for an electronic devices storage. The API retreives file information that is asked by the user via a front-end component by communicating with the database to retreive the information from the 5,000,000 records in the database using optimized SQL queries

The polished API functionality with error trapping is currently in the API folder. It contains muiltiple security checks to make sure the url cannot be easily tampered to access any sensitive information and is parsed correctly for Curl requests. Furtheremore it has been error-proofed against common vulnerabilities such as SQL injection. Tools used are PHP, AWS EC2 instance, Ubuntu Linux, nginx, HTML, CSS, mySQL and Shell scripting
