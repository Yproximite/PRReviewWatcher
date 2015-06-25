# PRReviewWatcher

### What the purpose of this project ?
The PR Review Watcher will post a list of checks you've defined as a Pull Request comment when a new pull request is created

The project can **save you time** when it comes to pull request reviews:

* avoid common pitfall/mistakes a developer can make
* make sure the developper has reviewed his own code

### How to install the project

Simply install the project via composer or via git. It was made

`composer require yproximite/pr-review-watcher`

### Configuration

#### Security

Enter your desired login/sha1 password inside the file `config/config.yml` in order to connect yourself to the application.

You can generate your sha1 password at [sha1](http://www.sha1-online.com/).

#### Credential

First of all, you'll need a Github token in order to post comment.

Check the [GitHub documentation](https://help.github.com/articles/creating-an-access-token-for-command-line-use/) to see how to create one .

#### Project

Then you add a project you want to hook into.
Configure webhook github by following this example : 

* Into your GitHub project, click to settings then to WebHooks & Services.
* Enter the url of the application + /api  Example : `https://[url_of_the_application]/api`, select application/json and choose only the event Pull Request.

![Example](http://i.imgur.com/GKK9Hk1.png)

You can configure when to post comment on new pull request:

* Post a checklist on a pull request **only if it's opened against `develop` branch**: enter "develop" in the field configured branches
* Post a checklist on a pull request opened **against all branches**: enter "all"

#### Vhost
nginx example: 

    server {
    	listen	80;
    	server_name prwatcher;
     
    	access_log	/var/log/nginx/prwatcher.access.log;
    	error_log	/var/log/nginx/prwatcher.error.log;
     
    	root path/of/project/web;
    	index index.php;
     
    	#location @rewrites {
    	#	#rewrite ^ /index.php last;
    	#	rewrite ^/(.*)$ index.php?url=$1 last;
    	#}
    
    	location ~ /\.ht {
    		deny all;
    	}
    
    	location ~ \.php$ {
    		fastcgi_index index.php;
       	 	fastcgi_split_path_info ^(.+\.php)(.*)$;
       		include fastcgi_params;
        		fastcgi_pass unix:/var/run/php5-fpm.sock;
       		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;	
     	}
    
    	location / { 
                    try_files $uri $uri/ /index.php?$query_string;
            }
    
    }