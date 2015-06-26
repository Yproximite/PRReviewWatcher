# PRReviewWatcher

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/PRReviewWatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/PRReviewWatcher/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yproximite/pr-review-watcher/v/stable)](https://packagist.org/packages/yproximite/pr-review-watcher) [![Total Downloads](https://poser.pugx.org/yproximite/pr-review-watcher/downloads)](https://packagist.org/packages/yproximite/pr-review-watcher) [![Latest Unstable Version](https://poser.pugx.org/yproximite/pr-review-watcher/v/unstable)](https://packagist.org/packages/yproximite/pr-review-watcher) [![License](https://poser.pugx.org/yproximite/pr-review-watcher/license)](https://packagist.org/packages/yproximite/pr-review-watcher)

### What the purpose of this project ?
The PR Review Watcher will post a list of checks you've defined as a Pull Request comment when a new pull request is created

The project can **save you time** when it comes to pull request reviews:

* avoid common pitfall/mistakes a developer can make
* make sure the developper has reviewed his own code

### Screenshots

![Example webinterface](http://i.imgur.com/zsnuTV0.png)

![Example pull request comment](http://i.imgur.com/mcRPRCU.png)

### How to install the project

Simply install the project via composer or via git and run `composer install`

`composer require yproximite/pr-review-watcher`

### Configuration

#### Security

Enter your desired login/sha1 password inside the file `config/config.yml` (`cp config/config.yml.example` to `config/config.yml` in order to login to the application.

You can generate your sha1 password at [sha1](http://www.sha1-online.com/).

#### Credential

First of all, you'll need a **Github token** in order to post comments.

Check the [GitHub documentation](https://help.github.com/articles/creating-an-access-token-for-command-line-use/) to see how to create one .

#### Project

Then you add a project you want to hook into.
Configure webhook github by following this example : 

* Inside your GitHub project, go to **settings** then to **webhooks & services**.
* Enter the url of the application + `/api`  Example : `https://[url_of_the_application]/api`, select `application/json` and choose only the event **Pull Request**.
* 

You can configure when to post comment on new pull request:

* Post a checklist on a pull request **only if it's opened against `develop` branch**: enter "develop" in the field configured branches
* Post a checklist on a pull request opened **against all branches**: enter "all"

#### Vhost
nginx example: 

    server {
    	listen	80;
    	server_name your.domain.name;
     
    	access_log	/var/log/nginx/prwatcher.access.log;
    	error_log	/var/log/nginx/prwatcher.error.log;
     
    	root path/of/project/web;
    	index index.php;
    
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
