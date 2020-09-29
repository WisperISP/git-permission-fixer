# git-permission-fixer

This utility is used to help copy linux username, usergroup, and file permission from one system to another after git clone. This utility is meant to be used to copy the file permissions from one enviorment to another identical enviorment. Since github does not store the files username, usergroup, and file permissions.

## Getting Started

### Installation

Clone the Repo:
```bash
git clone https://github.com/WisperISP/git-permission-fixer
```

Edit the Config: ** coming soon
```bash
nano config.json
```



## Export Git Repo Permissions

Run the Git Permission Export Script:
```bash
php git-permission-export.php
```

Move Export Json file to your Git Repo:
```bash
mv git-permission-fixer/git-permission-store.json your-repo-path/git-permission-store.json
```

Now go ahead and push your Repo to Github and now whenever you pull or clone your repo you can run the import script to import the permissions of the repo files.

## Import Git Repo Permissions

Before you run the import you should install this utility on the new enviorment.

Run the Git Permission Impport Script:
```bash
php git-permission-import.php
```

You can check the results by viewing the json file in the git-permission-fixer/ directory.



