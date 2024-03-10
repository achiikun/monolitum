# monolitum

**The non-reactive PHP framework.**

The purpose of this framework is to bring the philosophy of frameworks that uses a tree of components (like React, or Flutter) to PHP.

Its values:

- To have the **least dependencies** possible: by embedding 3rd party libraries into it.
- Making it executable on the **cheapest lowest-cost** _Apache-PHP-MySQL_ server stack out there.
  - The minimum PHP version is **5.6**, forward-compatible with the last released version.

The file [todo.md](todo.md) contains a list of what will be developed soon.

## Documentation

No documentation is written by now. The framework is in development.

## Demos

Demo projects will be available soon. PHP language has never been so pretty.

## Modules

The main module of the framework is called <code>monolitum</code>. The code is inside submodules (one level of nested packages).

An application that only uses a few submodules may omit including the rest. The file [modules.md](modules.md) contains the description of all of them and the dependency tree.

## External licences

There is some copy-pasted code in order this framework to be self-contained.

### Moment.php

Code of Moment.php is is copied into this repository. License (MIT) is in this repo:

https://github.com/fightbulc/moment.php

### JQuery

Code of JQuery is used. Licence (MIT) is in this repo:

https://github.com/jquery/jquery

### Bootstrap

Code of Twitter Bootstrap is used. Licence (MIT) is in this repo:

https://github.com/twbs/bootstrap

### Select2

Code of Select2 is used. Licence (MIT) is in this repo:

https://github.com/select2/select2

### Bootstrap Select2 Theme

Code of Bootstrap Select2 Theme is used. Licence (MIT) is in this repo:

https://github.com/apalfrey/select2-bootstrap-5-theme

### FontAwesome

Code of Font Awesome (Icons: CC BY 4.0) is used. Licence is in this repo:

https://github.com/FortAwesome/Font-Awesome

### QuillEditor

Code of quilljs is copied into this repository.
License (BSD-3-Clause) is in this repo:

https://github.com/quilljs/quill

Code of quill-delta-parser for PHP is copied into this repository.
It was refactored to make it compatible with PHP 5.6.
License (MIT) is in this repo:

https://github.com/nadar/quill-delta-parser

### Naucon/HtmlBuilder

Some code is based on Naucon's HtmlBuilder library. License (MIT) is in this repo:

https://github.com/naucon/HtmlBuilder
