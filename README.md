# monolitum

**The non-reactive PHP framework.**

The purpose of this framework is to bring the philosophy of frameworks that uses a tree of components (like React, or Flutter) yo PHP.

Its values:

- To have the **least dependencies** possible: by embedding 3rd party libraries into it.
- Making it executable on the **cheapest lowest-cost** _Apache-PHP-MySQL_ server stack out there.
  - The minimum PHP version is **5.6**, forward-compatible with the last released version.
 
## Documentation

No documentation is written by now. The framework is in development.

## Demos

Demo projects will be available soon. PHP language has never been so pretty.

## Modules

The main module of the framework is called <code>monolitum</code>. The code is inside submodules (one level of nested packages).

An application that only uses a few submodules may omit including the rest. The file [modules.md](modules.md) contains the description of all of them and the dependency tree.

The file [todo.md](todo.md) contains a list of what will be developed soon. 

## External licences

There is some copy-pasted code in order this framework to be self-contained.

### Moment.php

Code of Moment.php is is copied into this repository. License is in this repo:

https://github.com/fightbulc/moment.php

### Bootstrap

Code of Twitter Bootstrap is used. Licence is in this repo:

https://github.com/twbs/bootstrap

### FontAwesome

Code of Font Awesome is used. Licence is in this repo:

https://github.com/FortAwesome/Font-Awesome

### WangEditor

Code of WangEditor is copied into this repository. License is in this repo:

https://github.com/wangeditor-team/wangEditor

### Naucon/HtmlBuilder

Some code is based on Naucon's HtmlBuilder library. License is in this repo:

https://github.com/naucon/HtmlBuilder
