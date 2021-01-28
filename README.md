# EZ-Note Restful API

## Description

    A custom coded RESTFUL API with full CRUD functionality for use with
    EZ-Notes Vue front-end

## Set-up

    The API needs to connect to a MySQL/MariaDB database. Connection credentials should be stored in an env.php 
    file. The env_template.php file should be edited to include credentials and saved as env.php which is added 
    to .gitignore.

    The SQL database structure is shown in 'eznotes_db_structure' as an SQL file that can be imported into a 
    new database.

    The public folder should be placed in the web root of the SPA in an /api folder. This prevents browser http 
    cookie setting issues when sent from a different url. The remaining folders should be stored one level below 
    the web root to prevent illegal access.

## Use

### RESTFUL Paths are as follows

    GET         api/                    list all noticeboards
    POST        api/                    create new noticeboard
    PUT         api/#                   edit board #
    DELETE      api/#                   delete board #

    GET         api/#                   list all notes tacked to noticeboard #
    POST        api/#                   create a new note on notice board #
    PUT         api/#/##                edit note ## on board #
    DELETE      api/#/##                delete note ## on board #

    ###Authorisation Paths

    POST        login                   supply email/password return JWT 
                                        Access/Refresh Tokens in Http Cookies

    POST        register                supply email/password return JWT 
                                        Access/Refresh Tokens in Http Cookies
                                        
    POST        refresh                 supply Reresh Token as Http Cookie
                                        return Access Token Cookies