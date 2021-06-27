<?php

require_once('vendor/autoload.php');

use DiscordWebhook\Webhook;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;

$app = AppFactory::create();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
    try {
        return $handler->handle($request);
    } catch (HttpNotFoundException $httpException) {
        $renderer = new PhpRenderer('templates/');
        return $renderer->render(new Response(), "main.php", []);
    }
});

$app->get('/', function ($request, $response, array $args) {
    $renderer = new PhpRenderer('templates/');
    return $renderer->render($response, "main.php", $args);
});

$app->get('/remove', function ($request, $response, array $args) {
    $renderer = new PhpRenderer('templates/');
    return $renderer->render($response, "remove.php", $args);
});

$app->group('/api/v1', function (RouteCollectorProxy $group) {
    $group->post('/insert[/]', function ($request, $response, array $args) {
        if (isset($_POST['scribblehub'], $_POST['discord'])) {
            $discord = validate_discord($_POST['discord']);
            $scribble = validate_scribble($_POST['scribblehub']);

            if (!$discord || !$scribble) {
                $response->getBody()->write(json_encode(['response' => 'A link (either ScribbleHub or Discord Webhook link) is invalid!', 'code' => 401]));
                return $response->withStatus(401);
            }

            $wh = new Webhook([$_POST['discord']]);
            $disResponse = $wh->setMessage('This is a test message from Amelia! (You may delete this message now).')->setAvatar('https://cdn.discordapp.com/avatars/786464598835986483/8175d0e1793e99b786032be669537a4c.png?size=4096')->setUsername('Amelia')->send();

            if (!$disResponse) {
                $response->getBody()->write(json_encode(['response' => 'The webhook URL is invalid, please read the guide by pressing the question mark next to Discord Webhook field!', 'code' => 401]));
                return $response->withStatus(401);
            }

            $client = new GuzzleHttp\Client();
            $res = $client->post($_ENV['node'].'/insert', ['form_params' => ['token' => $_ENV['token'], 'scribblehub' => $_POST['scribblehub'], 'discord' => $_POST['discord']]]);
            if ($res->getStatusCode() != 200) {
                $response->getBody()->write(json_encode(['response' => (string) $res->getBody(), 'code' => 401]));
                return $response->withStatus(401);
            }

            // We'll return a 204 which means the request was accepted with no body.
            return $response->withStatus(204);
        } else {
            return $response->withStatus(401);
        }
    });

    $group->post('/remove[/]', function ($request, $response, array $args) {
        if (isset($_POST['scribblehub'], $_POST['discord'])) {
            $discord = validate_discord($_POST['discord']);
            $scribble = validate_scribble($_POST['scribblehub']);

            if (!$discord || !$scribble) {
                $response->getBody()->write(json_encode(['response' => 'A link (either ScribbleHub or Discord Webhook link) is invalid!', 'code' => 401]));
                return $response->withStatus(401);
            }

            $client = new GuzzleHttp\Client();
            $res = $client->post($_ENV['node'].'/remove', ['form_params' => ['token' => $_ENV['token'], 'scribblehub' => $_POST['scribblehub'], 'discord' => $_POST['discord']]]);
            if ($res->getStatusCode() != 200) {
                $response->getBody()->write(json_encode(['response' => (string) $res->getBody(), 'code' => 401]));
                return $response->withStatus(401);
            }

            // We'll return a 204 which means the request was accepted with no body.
            return $response->withStatus(204);
        } else {
            return $response->withStatus(401);
        }
    });

    // This is where Amelia websocket will send its request whenever a new update comes.
    // The reason why we are handling it here instead of the websocket is to reduce the websocket-client's size.
    $group->post('/send[/]', function ($request, $response, array $args) {
        if (isset($_POST['token'], $_POST['webhook'], $_POST['title'], $_POST['author'], $_POST['link']) && $_POST['token'] === $_ENV['token']) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $link = $_POST['link'];

            $wh = new Webhook([$_POST['webhook']]);
            $disResponse = $wh->setMessage("\uD83D\uDCD6 **$title by $author**\n$link")->setAvatar('https://cdn.discordapp.com/avatars/786464598835986483/8175d0e1793e99b786032be669537a4c.png?size=4096')->setUsername('Amelia')->send();
            if(!$disResponse) {
                return $response->withStatus(401);
            }

            return $response->withStatus(204);
        }
    });
});

// Validation methods.

function validate_scribble(string $link): bool
{
    $exploded = explode("/", $link);
    if (!(startsWith($link, 'https://scribblehub.com/') || startsWith($link, 'https://www.scribblehub.com/')) || count($exploded) < 4) {
        return false;
    }

    if (!($exploded[3] === 'profile' || $exploded[3] === 'series')) {
        return false;
    }

    if (is_nan($exploded[4])) {
        return false;
    }

    return true;
}

function validate_discord(string $link): bool
{
    $exploded = explode("/", $link);
    if (!(startsWith($link, 'https://discord.com/api/webhooks/') || startsWith($link, 'https://www.discord.com/api/webhooks/')) || count($exploded) < 6) {
        return false;
    }

    if (is_nan($exploded[5])) {
        return false;
    }

    if (!isset($exploded[6]) || empty($exploded[6])) {
        return false;
    }

    return true;
}

// Helper methods.
// These are stuff that somehow PHP didn't add on older versions (7.4 PHP) FOR SOME REASON!!!! WTF?

// Attribution: https://stackoverflow.com/a/834355/15879992 (too lazy to write my own).
function startsWith($haystack, $needle)
{
    return substr($haystack, 0, strlen($needle)) === $needle;
}

// Attribution: https://stackoverflow.com/a/834355/15879992 (the code is on the comments.)
function endsWith($haystack, $needle)
{
    return substr($haystack, -strlen($needle)) === $needle;
}

$app->run();
