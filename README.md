## ✨ Amelia Light
This is a minimal version of Amelia which as its tagline says `Long ago, a star shone bright in the skies` is the lightest version of Amelia
with extremely minimal functionalities and is limited to only adding and removing RSS feeds with no option to subscribe.

## 💬 What is the purpose of this lightweight version?
The purpose of having this version is to make it easier for non-techie users to have their story updates immediately notified to their servers
whenever they update (or if a favorite author of theirs update).

## ❤️ Do I have to do technical stuff to make this work?
If you don't want to handle technical stuff like privately hosting it, then feel free to use the publicly hosted version at https://amelia.manabot.fun
which is hosted by me and will be constantly updated for any bug fixes, security patches, etc.

## ✔️ Pre-requisites
* PHP > 7.4
* OpenJDK > 11
* NGINX (or Apache)
* Composer
* Knowledge of using all three.

## 🧰 Environmental Variables
For the Environmental Variables of the websocket client, please check its README.md which explains it more deeply, as for the website,
here are the values on `.env` that you will need.
```env
token=AUTHENTICATION (same as amelia_auth on client).
node=127.0.0.1:9661 (URI to the client).
```

## ⚙️ How to install?
First, make sure you have the websocket client running by reading its README (since I don't want to explain it here). Afterwards, copy everything
from the `web` folder and paste it on its independent folder **which is being used by NGINX**, more details can be googled. After pasting all the files,
open a terminal and execute the command: `composer install` and wait for it to install everything.

After all the dependencies are installed, head to your `NGINX` config (Ubuntu: `/etc/nginx/sites_enabled/{site}.conf`) and add these two:
```conf
	location / {
    		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~* \.(git|env)$ {
    		deny all;
	}

```

Then do a NGINX reload using the command: `service nginx reload` and you should be done.
