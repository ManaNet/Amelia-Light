tippy('#scribblehub-info', {
    content: 'The link to the ScribbleHub story or the author\'s profile.',
    animation: 'scale',
    inertia: true
});

tippy('#discord-webhook', {
    content: 'The link to the Discord webhook, press the question mark for more information.',
    animation: 'scale',
    inertia: true
});

function submit() {
    discord = document.getElementById("webhook-link").value;
    scribblehub = document.getElementById("scribblehub-link").value;

    valid_discord = validate_discord(discord);
    valid_sh = validate_scribble(scribblehub);
    if (!valid_discord) {
        error_button("webhook-link");
    }

    if (!valid_sh) {
        error_button("scribblehub-link");
    }

    if (!valid_discord || !valid_sh)
        return;

    fetch('/api/v1/insert', {
        method: 'POST',
        body: new URLSearchParams({
            'scribblehub': scribblehub,
            'discord': discord
        })
    }).then(response => {
        if (response.status != 204) {
            if(response.status == 401 ) {
                response.json().then(r => new Noty({
                    type: 'error',
                    text: 'An error occurred: ' + (response.status == 401 ? r.response :
                    response.status + " " + response.statusText) // The API will never send a non-401 error.
                    , timeout: 2000
                }).show());
            } else {
                new Noty({
                    type: 'error',
                    text: 'An error occurred: ' + response.status + " " + response.statusText // The API will never send a non-401 error.
                    , timeout: 2000
                }).show()
            }
            error_button("webhook-link");
            error_button("scribblehub-link");

            return;
        }

        new Noty({
            type: 'success',
            text: 'An test message was sent successfully which means the specified channel will now receive notifications when the story or any of the user\'s story updates!',
            timeout: 2000
        }).show();
    });
}

function error_button(button) {
    document.getElementById(button).style = "border:1px solid #fe6f6f;";
    setTimeout(function () {
        document.getElementById(button).style = "border:1px solid #4eaaaf;"
    }, 2000);
}

// These are methods used to validate client-side.
// They are still validated on server-side, so don't expect to exploit this.

function validate_scribble(data) {
    if (!(data.startsWith('https://scribblehub.com') || data.startsWith('https://www.scribblehub.com/')) || data.split('/').length < 4)
        return false;

    pref = data.split("/")[3];
    if (pref !== 'profile' && pref !== 'series')
        return false;

    if (isNaN(data.split('/')[4]))
        return false;

    return true;
}

function validate_discord(data) {
    if ((!data.startsWith("https://discord.com/api/webhooks/") && !data.startsWith("https://www.discord.com/api/webhooks/")) || data.split('/').length < 6)
        return false;

    if (isNaN(data.split("/")[5]))
        return false;

    last = data.split('/')[6];
    if (last == null || last.length == 0)
        return false;

    return true;
}