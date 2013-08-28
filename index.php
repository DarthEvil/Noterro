<?php
require_once 'config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Model\Messages;

//Enable debug mode
if (DEBUG)
{
    $app['debug'] = true;
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// connect

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/app/view',
));

$app['database_config'] = $database_config;
$app['mailbox_config']  = $mailbox_config;
$app['base_config']  = $base_config;

$app->get('/', function() use($app) {

    $data = array();

    require_once 'app/model/Messages.php';
    require_once 'app/model/Tags.php';

    $model = new Model\Messages($app);
    $tags_model = new Model\Tags($app);

    $tags = array();

    $data['items'] = $model->getMessagesList($tags);
    $data['tags'] = $tags_model->getList();
    $data['selected_tags'] = $tags;

    return $app['twig']->render('index.twig', $data);
});

$app->post('/', function(Request $request) use($app) {

    $data = array();

    $tags = $request->get("tags", array());

    require_once 'app/model/Messages.php';
    require_once 'app/model/Tags.php';

    $model = new Model\Messages($app);
    $tags_model = new Model\Tags($app);

    $data['items'] = $model->getMessagesList($tags);
    $data['tags'] = $tags_model->getList();
    $data['selected_tags'] = $tags;

    return $app['twig']->render('index.twig', $data);

});

$app->get('/crontab/mail/', function() use($app) {

    $mailbox_config = $app['mailbox_config'];
    $general_config = $app['base_config'];

    require_once 'app/model/Messages.php';
    require_once 'app/model/Tags.php';

    $model = new Model\Messages($app);
    $tags_model = new Model\Tags($app);

    $server = new \Fetch\Server($mailbox_config['imap']['host'], $mailbox_config['imap']['port']);
    $server->setAuthentication($mailbox_config['auth']['login'], $mailbox_config['auth']['password']);

    $mailboxes = $mailbox_config['folders'];

    $inserted   = 0;
    $total      = 0;

    foreach ($mailboxes as $mailbox)
    {
        $server->setMailBox($mailbox);
        //$messages = $server->search("UNSEEN");
        $messages = $server->search("ALL");

        foreach ($messages as $message)
        {
            $headers = explode($general_config['separater'], $message->getSubject());
            $tags = array();
            foreach ($headers as $header)
            {
                $header = trim($header);
                if (!empty($header)) {
                    $tags[] = $header;
                }
            }

            if (!empty($general_config['key_phrase'])
                && $tags[0] != $general_config['key_phrase']
                && count($tags) < 2
            ) {
                continue;
            } else if (!empty($general_config['key_phrase'])) {
                unset($tags[0]);
            }

            $itags = array();
            foreach ($tags as $tag)
            {
                $itags[] = $tag;
            }

            $date = $message->getDate()->getTimestamp();

            $text = $message->getMessageBody(false);

            $item =  new \Model\Messages\Message($date, $text, $itags);

            $total++;
            if ($model->Insert($item)) {
                $inserted++;
                foreach ($tags as $tag) {
                    $tags_model->insert($tag);
                }
            }
        }
    }

    echo $inserted.' - '.$total;
    return true;
});

$app->run();