Versioner
=========

Versioner is a tool that can be used to increment the version in a VERSION 
file (file containing the version number of you application). You can
choose the update the `major`, `minor` or `patch` number and all other
parts are updated according to [SEMVER 2.0](http://semver.org/spec/v2.0.0.html).

Each [release on Github](https://github.com/mvriel/Versioner/releases) contains
a pre-compiled PHAR file that can be used.

Usage:

    php versioner.phar increment [VERSION filename]

You can use the `--part` option with the value `major`, `minor` or `patch` to
indicate which part needs to be updated, by default the minor number is updated.


