rktranslatelog
==============

Keeping track of translation strings in a Magento store can be a hastle. After adding an extra language to an installation this extension will keep track of untranslated strings. 

A log file is created with the string, module and ISO language code and a translate.csv with unique strings is created under `var/translate/[aa]_[AA]/translate.csv`.

Both options can be enabled or disabled separately from the `system > configuration` section.

sidenote: please make sure the `var/translate/` directory is writable
