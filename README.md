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

Enter your desired login/sha1 password inside the file `config.yml` in order to connect yourself to the application

#### Credential

First of all, you'll need a Github token in order to post comment.

#### Project

Then you add a project you want to hook into.
Configure webhook github by following this example

You can configure when to post comment on new pull request:

* Post a checklist on a pull request **only if it's opened against `develop` branch**: enter "develop" in the field configured branches
* Post a checklist on a pull request opened a**gainst all branches**: enter "all"

#### Vhost
nginx example:
apache example:
