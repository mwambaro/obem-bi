# [OBEM](https://obem.bi)

Web Application for the employment government service called `OBEM: Office du Burundi pour l'Emploi et la Main d'oeuvre.`
That is French for `Office of Burundi for Employment and Manpower (OBEM)`

# Laravel Install Guidelines

Installed on Windows 10 version [] according to instructions at:
[this blog](https://cynoteck.com/blog-post/installing-laravel-8-on-windows-10-xampp/)

Extension issues met fixed according to instructions at:
[this issue](https://stackoverflow.com/questions/52734707/your-requirements-could-not-be-resolved-to-an-installable-set-of-packages-for-la)

Install XAMPP: Ignore the UAC remarks but install to C:\xampp folder.
Download it from [this site](https://downloadsapachefriends.global.ssl.fastly.net/8.1.6/xampp-windows-x64-8.1.6-0-VS16-installer.exe?from_af=true)

`php artisan migrate` error:
        `Illuminate\Database\QueryException
        could not find driver (SQL: select * from information_schema.tables where table_schema = obem_mysql_db and table_name = migrations and table_type = 'BASE TABLE')`
        SOLUTION: Go to php installation folder, find php.ini file and the line that comments out PDO MySQL extension,
                  that is, `;extension=pdo_mysql` and remove the comment character so that it reads `extension=pdo_mysql`.

Avast warned of file `server.php` being infected with `IDP.Generic` malware so I had to create an exception for 
`php artisan serve` to return the welcome page for Laravel.

# React and Bootstrap Install Guide lines

Manual packages, since Afriregister, in the offer purchased, does not give us power to use Nodejs or NPM, or
any other package management tool, mind you.


# Access token to repo

token: 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
validity: 30 days
[instructions](https://stackoverflow.com/questions/42148841/github-clone-with-oauth-access-token)
example: `git clone https://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@github.com/mwambaro/obem-bi.git`