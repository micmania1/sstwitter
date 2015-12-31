<?php

class LatestTweetsWidget extends Widget
{
    
    public static $title = "Latest Tweets";

    public static $cmsTitle = "Latest Tweets";

    public static $db = array(
        "TwitterHandle" => "Varchar(255)",
        "NumberOfTweets" => "Int",
    );

    public function Tweets()
    {
        $twitterApp = TwitterApp::get()->first();
        if (!$twitterApp) {
            return null;
        }

        $siteConfig = SiteConfig::current_site_config();
        $twitter = $twitterApp->getTwitter();
        $twitter->setAccess(new OAuthToken($twitterApp->TwitterAccessToken, $twitterApp->TwitterAccessSecret));

        if ($twitter->hasAccess()) {
            $result = $twitter->api("1.1/statuses/user_timeline.json", "GET", array(
                "screen_name" => $this->TwitterHandle,
                "count" => $this->NumberOfTweets
            ));

            if ($result->statusCode() == 200) {
                $rawTweets = json_decode($result->body(), true);
                if (count($rawTweets) > 0) {
                    $tweets = new ArrayList();
                    foreach ($rawTweets as $tweet) {
                        // Parse tweet links, users and hashtags.
                        $parsed = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" target=\"_blank\">\\2</a>'", $tweet['text']);
                        $parsed = preg_replace("#(^|[\n ])@([A-Za-z0-9\_]*)#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" target=\"_blank\">@\\2</a>'", $parsed);
                        $parsed = preg_replace("#(^|[\n ])\#([A-Za-z0-9]*)#ise", "'\\1<a href=\"http://www.twitter.com/search?q=\\2\" target=\"_blank\">#\\2</a>'", $parsed);

                        $t = new ArrayData(array());
                        $t->Tweet = DBField::create_field("HTMLText", $parsed, "Tweet");
                        $t->TweetDate = DBField::create_field("SS_Datetime", strtotime($tweet['created_at']));
                        $t->TweetLink = DBField::create_field("Varchar", "http://www.twitter.com/" . rawurlencode($tweet['user']['screen_name']) . "/status/" . rawurlencode($tweet['id_str']));
                        $tweets->push($t);
                    }
                    return $tweets;
                }
            }
        }
        return null;
    }

    public function getCMSFields()
    {
        $fields = new FieldList();
        $fields->push(TextField::create("TwitterHandle", "Twitter Handle"));
        $fields->push(NumericField::create("NumberOfTweets", "Number of Tweets"));
        return $fields;
    }
}

class LatestTweets_Controller extends Widget_Controller
{
}
