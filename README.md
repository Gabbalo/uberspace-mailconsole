# uberspace-mailconsole

An administration-console for temporary email-addresses using .qmail-files on uberspace (or similar working hosters)

This console helps you adding .qmail-files for redirect-purposes on your [uberspace-webspace](https://uberspace.de).
You can choose between self-defined mailbox-names (so you can assign it to a specific service for example) or you can generate a random address to throw away.
In both cases, you can add a time-to-live, so the mailbox is whiped automatically after a given period. Therefore you have to set-up a cron-job or something similar.
This project ist based on an idea from [8300111.de](http://www.8300111.de/qdated-im-uberspace/), but is made from scratch.
