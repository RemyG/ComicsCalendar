---
layout: page
title: Download
---

# {{ page.title }}

## With Git

* Clone the project:

		$ mkdir /var/www/comicscalendar
		$ cd /var/www/comicscalendar
		$ git clone https://github.com/RemyG/ComicsCalendar.git .

* Fetch the submodule for Propel ORM:

		$ git submodule init
		$ git submodule update

## Without git

* Download the application ([ZIP File](https://github.com/RemyG/ComicsCalendar/zipball/master) or [TAR Ball](https://github.com/RemyG/ComicsCalendar/tarball/master)), and extract.
* Download [Propel](http://propelorm.org/download.html) and extract in `application/plugins/propel`.