====
IMAP
====

This is the IMAP extension extracted from the PHP source distribution.
PHP versions after PHP 8.3 no longer bundle this extension.

About this fork
---------------

This repository is a maintained fork of `php/pecl-mail-imap
<https://github.com/php/pecl-mail-imap>`_, which describes itself as an
archive. It keeps the same extension name, code and license.

Compared with the last PECL release (1.0.3), it adds:

- support for PHP 8.6 and php-src master
- installation with `PIE <https://github.com/php/pie>`_
- automatic detection of the Kerberos and SSL support that c-client was
  built with, so a plain ``./configure`` works with distribution packages
- a clear error from ``./configure`` when c-client is not installed
- CI that runs the test suite against Dovecot on PHP 8.3 to 8.6, NTS and
  ZTS, and on Alpine Linux

The extension is still not safe to use from several threads in the same
process (for example FrankenPHP). ZTS builds of PHP that run one thread per
process, such as the CLI and PHP-FPM, work.

The documentation is still available as part of the `PHP Documentation
<https://www.php.net/imap>`_.

Installing with PIE
-------------------

PIE builds the extension from source, so you need c-client (see below), a C
compiler, ``autoconf`` and ``make``, and ``unzip`` to unpack the download. On
the official PHP Docker images the build tools are listed in
``$PHPIZE_DEPS``. Then::

	pie install sirlouen/imap

Windows is not supported through PIE yet.

Installing with pecl
--------------------

Each release also comes with a package for ``pecl``, which needs the same
build tools as PIE except ``unzip``. ``pecl`` cannot install it straight from
the GitHub URL, so download it first::

	curl -LO https://github.com/SirLouen/pecl-mail-imap/releases/download/1.1.1/imap-1.1.1.tgz
	pecl install imap-1.1.1.tgz

If ``pecl`` stops with "Extension 'imap' already loaded", remove your
distribution's imap package first, for example ``apt remove php8.4-imap``.

Then add ``extension=imap`` to your ``php.ini``. On the official PHP Docker
images, run ``docker-php-ext-enable imap`` instead.

Installation Issues
-------------------

In order for this extension to compile, you need to have the ``c-client``
installed. Debian 13, Ubuntu 26.10, Fedora and Homebrew no longer package
it.

Debian and Ubuntu
~~~~~~~~~~~~~~~~~

On Debian 12, Ubuntu 24.04 and Ubuntu 26.04 you can install it with
``apt-get``. As this distribution installed library is compiled with Kerberos
and SSL support, you need to make sure to have these development libraries
installed too.

You can install all of these, plus ``unzip`` for PIE, with::

	apt-get install libc-client-dev libkrb5-dev libssl-dev unzip

Kerberos and SSL support are enabled automatically, because the system
installed c-client requires them.

Alpine Linux
~~~~~~~~~~~~

Alpine's c-client is built with SSL support only::

	apk add imap-dev openssl-dev
