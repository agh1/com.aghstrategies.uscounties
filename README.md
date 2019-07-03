We're happy to introduce the US Counties extension for CiviCRM, allowing an easy method to load all the counties in the United States.

Using the extension
-------------------

Enabling the extension runs the county loader, which loads all the counties in each state if they aren't already in your database.  From that point, the extension does nothing.  You can even disable the extension, and the counties will stay there.  Uninstalling the extension also does nothing, for an important reason: it could cause data loss by deleting data stored in addresses.

In addition to using the extension to load the counties, you'll want to go to Administer - Localization - Address Settings and check County under Address Editing, which will display the County field when you edit a contact's record.

History
-------

Counties existed in CiviCRM since very early in the project, and they were linked to the appropriate states.  The database is shipped with five counties from California, and many users loaded counties for specific states.  However, the interface didn't chain county selections with the states, and many county names exist in multiple states.  This chaining was added for CiviCRM 3.4/4.0, but because not everyone needs over 3,000 U.S. counties in their database, the CiviCRM database still only ships with the five counties.

Starting then, the counties were included in CiviCRM in a gzipped SQL file, but many users find it difficult to unzip and load the file.  This approach aims to make it an easy process for all site administrators to implement counties.

Other Countries
---------------

You can write your own extension for your country's counties or county equivalents.  Just copy this extension and do the following:

    rename the directory to something else;
    rename uscounties.php to something else;
    edit the info.xml file to give it a new name, description, file name for the main extension file (instead of uscounties), and maintainer; and
    replace the array of counties in your renamed uscounties.php with your country's states and counties.

Changelog
---------

1.1 - Compatibility with 4.6 and 4.7 (no changes to code or counties)
1.0 - Initial version
