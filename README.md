### Welcome to Easy Alert!

## What is Easy Alert?

The last months I had to setup a monitoring system and I was really
frustrated how unintuitional this was. You had to edit a lot of
config files, restart the daemon to take effect and so on. If you go
a step farther and want to process the performance data, it is not
easy to get a nice web interface that's easy to use and doesn't look
like it is 20 years old. You have to mix several programs, and that
isn't fun.

## Soooo...
I decided to start my own monitoring project. Completely based on
object orientation, using ajax, the whole web 2.0 thing.

I have some great features in mind, some of them are already
implemented, some not:

 - PHP-based backend daemon
    - binds on port 9786
    - pure PHP
    - supports daisy chaining (you will be able to query a host
      that is not directly reachable by querying a host that is
      able to do it)
    - communication with serialized PHP objects
 - Frontend
    - Authentication, ACL (user-based, group-based), notification
      groups
    - Host management with support for daisy chaining
    - Performance graphs
       - every check class contains information about what
         performance-data it can deliver and will display the
         graphs out-of-the-box
    - Cron-executed PHP-based poller
    - Based on Apache, PHP, MySQL, Cron
  - under BSD license


## I hope...

This sound great to you! I hope that the interest will be enormous
and that there will be other developers that want to contribute to
the project!
