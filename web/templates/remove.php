<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amelia-chan | Remove Webhook</title>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css"
        integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/solid.min.css"
        integrity="sha512-jQqzj2vHVxA/yCojT8pVZjKGOe9UmoYvnOuM/2sQ110vxiajBU+4WkyRs1ODMmd4AfntwUEV4J+VfM6DkfjLRg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="secret" content="https://manabot.fun">
    <meta name="theme-color" content="#fcf14c">
    <meta name="og:title" content="Amelia">
    <meta name="og:description" content="A ScribbleHub-focused RSS Discord notifier or Discord bot that is well-loved!">
    <meta name="og:image" content="https://cdn.discordapp.com/avatars/786464598835986483/8175d0e1793e99b786032be669537a4c.png?size=4096">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,400;0,700;1,200&display=swap" rel="stylesheet">
    <style>
        :root{color:#fff}html{height:100%;width:100%}body{background-color:#1a1a1a;background:linear-gradient(180deg,rgba(253,255,131,.2) 0,rgba(0,0,0,.8) 90%),url(https://cdn.discordapp.com/avatars/786464598835986483/8175d0e1793e99b786032be669537a4c.png?size=4096) 1%,#1a1a1a;background-repeat:no-repeat;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover}.container{padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px}.header{display:flex;flex-direction:row;padding:20px,5px}.text-header{display:flex;flex-direction:column}.Amelia-Icon{border-radius:50%;width:50px;height:50px;padding-right:10px}.hero-text{font-family:Righteous;font-style:normal;font-weight:400;font-size:32px;line-height:0;margin-bottom:10px}.header p{font-family:Roboto;font-style:normal;font-weight:400;font-size:12px;line-height:0;margin-top:10px}.form{position:absolute;top:40%;transform:translateY(-40%)}.form h2{font-family:Overpass;font-style:normal;font-weight:700;font-size:24px;padding-bottom:10px;margin-bottom:0}.form-question{padding-top:0;margin-bottom:20px}.form-question i{font-size:16px;padding-top:0}.form-question p{font-family:Overpass;font-style:normal;font-weight:400;font-size:18px;margin-top:5px;margin-bottom:0}.form-question input{margin-top:0;color:#fff;height:45px;font-family:Overpass;font-style:normal;font-weight:light;font-size:12px;padding:10px;background:rgba(30,30,30,.9);border:1px solid #4eaaaf;box-sizing:border-box;border-radius:4px;transition-duration:.6s}@media screen and (min-width:768px){.form-question input{width:727px}}@media screen and (max-width:760px){.form-question input{width:90vw}}.form-question input:hover{border:1px solid #95f9ff;transform:scale(1.02)}.button{padding:12px 60px;text-decoration:none;color:#fff;background:rgba(26,26,26,.9);border:1px solid #4eaaaf;box-sizing:border-box;border-radius:4px;transition-duration:.6s}.button:hover{border:1px solid #92e5ff;background-color:#92e5ff}.love{position:absolute;bottom:0}.love .fa-heart{color:#fe6f6f;padding-right:5px}
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://cdn.discordapp.com/avatars/786464598835986483/8175d0e1793e99b786032be669537a4c.png?size=4096"
                alt="Amelia-chan" class="Amelia-Icon">
            <div class="text-header">
                <h1 class="hero-text">Amelia-chan</h1>
                <p>Long ago, a star shone bright in the skies.</p>
            </div>
        </div>
        <div class="form">
            <h2>Want to stop Amelia from notifying your entire server <br>when a certain storyor user's story updates?</h2>
            <div class="form-question">
                <p>Discord Webhook <a href="https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks" target="_blank" style="color: white; text-decoration: none;"><i id="discord-webhook" class="fas fa-question-circle"></i></a></p>
                <input type="text" id="webhook-link" class="form-text" placeholder="https://discord.com/api/webhooks/858589064848080898/dUGMq-WtxthxgTm8yomI8F4MkQE0S15KqJRDOIfSYzcHQNrrgPJpt8w5hC2iFe-Vcphh">
            </div>
            <div class="form-question" style="padding-bottom: 10px;">
                <p>ScribbleHub Link <i id="scribblehub-info" class="fas fa-question-circle"></i></p>
                <input type="text" id="scribblehub-link" class="form-text" placeholder="https://www.scribblehub.com/profile/24680/mihou/">
            </div>
            <a href="#" class="button" id="submit" onclick="submit();">Remove</a>
        </div>
        <div class="love">
            <h3><i class="fas fa-heart"></i> Made with love by <a href="https://patreon.com/mihou" style="color: #FE6F6F; text-decoration: none;">Shindou Mihou</a></h3>
        </div>
    </div>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet"href="https://unpkg.com/tippy.js@6/animations/scale.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js" integrity="sha512-lOrm9FgT1LKOJRUXF3tp6QaMorJftUjowOWiDcG5GFZ/q7ukof19V0HKx/GWzXCdt9zYju3/KhBNdCLzK8b90Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" integrity="sha512-NXUhxhkDgZYOMjaIgd89zF2w51Mub53Ru3zCNp5LTlEzMbNNAjTjDbpURYGS5Mop2cU4b7re1nOIucsVlrx9fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function submit(){discord=document.getElementById("webhook-link").value,scribblehub=document.getElementById("scribblehub-link").value,valid_discord=validate_discord(discord),valid_sh=validate_scribble(scribblehub),valid_discord||error_button("webhook-link"),valid_sh||error_button("scribblehub-link"),valid_discord&&valid_sh&&fetch("/api/v1/remove",{method:"POST",body:new URLSearchParams({scribblehub:scribblehub,discord:discord})}).then(t=>{if(204!=t.status)return console.log(t.body),401==t.status?t.json().then(e=>new Noty({type:"error",text:"An error occurred: "+(401==t.status?e.response:t.status+" "+t.statusText),timeout:2e3}).show()):new Noty({type:"error",text:"An error occurred: "+t.status+" "+t.statusText,timeout:2e3}).show(),error_button("webhook-link"),void error_button("scribblehub-link");new Noty({type:"success",text:"The bot will now stop sending notification updates for that exact ScribbleHub user or story.",timeout:2e3}).show()})}function error_button(t){document.getElementById(t).style="border:1px solid #fe6f6f;",setTimeout(function(){document.getElementById(t).style="border:1px solid #4eaaaf;"},2e3)}function validate_scribble(t){return!(!t.startsWith("https://scribblehub.com")&&!t.startsWith("https://www.scribblehub.com/")||t.split("/").length<4)&&(pref=t.split("/")[3],("profile"===pref||"series"===pref)&&!isNaN(t.split("/")[4]))}function validate_discord(t){return!(!t.startsWith("https://discord.com/api/webhooks/")&&!t.startsWith("https://www.discord.com/api/webhooks/")||t.split("/").length<6)&&(!isNaN(t.split("/")[5])&&(last=t.split("/")[6],null!=last&&0!=last.length))}tippy("#scribblehub-info",{content:"The link to the ScribbleHub story or the author's profile.",animation:"scale",inertia:!0}),tippy("#discord-webhook",{content:"The link to the Discord webhook, press the question mark for more information.",animation:"scale",inertia:!0});
    </script>
</body>

</html>