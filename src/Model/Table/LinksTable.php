<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BundlesTable&\Cake\ORM\Association\BelongsToMany $Bundles
 * @property \App\Model\Table\StatisticsTable&\Cake\ORM\Association\HasMany $Statistics
 *
 * @method \App\Model\Entity\Link get($primaryKey, $options = [])
 * @method \App\Model\Entity\Link newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Link[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Link|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Link saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Link patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Link[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Link findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $BundlesLinks
 */
class LinksTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Users');
        $this->belongsToMany('Bundles');
        $this->hasMany('Statistics');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('url', 'Please add a URL.')
            ->add('url', 'checkUrl', [
                'rule' => function ($value, $context) {
                    $url_parts = parse_url($value);

                    if ($url_parts['scheme'] == 'magnet') {
                        return true;
                    }

                    if (\Cake\Validation\Validation::url($value)) {
                        return true;
                    }

                    return false;
                },
                'last' => true,
                'message' => __('URL is invalid.'),
            ])
            ->add('url', 'checkProtocol', [
                'rule' => function ($value, $context) {
                    $scheme = parse_url($value, PHP_URL_SCHEME);

                    if (in_array($scheme, ['http', 'https', 'magnet'])) {
                        return true;
                    }

                    return false;
                },
                'last' => true,
                'message' => __('http, https and magnet urls only allowed.'),
            ])
            /*
            ->add('url', 'uniqueURL', [
                'rule' => function ($value, $context) {
                    $count = $this->find('all')
                        ->where([
                            'url' => $value,
                            'alias' => $context['data']['alias'],
                            'user_id' => $context['data']['user_id'],
                            'type' => $context['data']['type'],
                            'status' => 1
                        ])
                        ->count();


                    if( isset($context['data']['id']) && !empty($context['data']['id']) ) {
                        //$count->where(['id !=' => $context['data']['id']]);
                    }


                    if ($count > 0) {
                        return false;
                    }
                    return true;
                },
                'last' => true,
                'message' => __('This link is already existing.')
            ])
            */
            ->add('url', 'disallowedDomains', [
                'rule' => function ($value, $context) {
                    $disallowed_domains = explode(',', get_option('disallowed_domains'));
                    $disallowed_domains = array_map('trim', $disallowed_domains);
                    $disallowed_domains = array_map('strtolower', $disallowed_domains);
                    $disallowed_domains = array_filter($disallowed_domains);
                    $disallowed_domains = array_merge($disallowed_domains, array_values(get_all_domains_list()));

                    if (empty($disallowed_domains)) {
                        return true;
                    }

                    $url_main_domain = strtolower(parse_url($value, PHP_URL_HOST));

                    if (in_array($url_main_domain, $disallowed_domains)) {
                        return false;
                    }

                    $disallowed_domains = array_filter($disallowed_domains, function ($value) {
                        return substr($value, 0, 2) === "*.";
                    });

                    if (empty($disallowed_domains)) {
                        return true;
                    }

                    $disallowed_domains = array_map(function ($value) {
                        return substr($value, 1);
                    }, $disallowed_domains);

                    foreach ($disallowed_domains as $disallowed_domain) {
                        if (preg_match("/" . preg_quote($disallowed_domain, '/') . "$/", $url_main_domain)) {
                            return false;
                            break;
                        }
                    }

                    return true;
                },
                'last' => true,
                'message' => __('This domain is not allowed on our system.'),
            ])
            ->add('url', 'checkGoogleSafeUrl', [
                'rule' => function ($value, $context) {
                    $google_safe_browsing_key = get_option('google_safe_browsing_key');

                    if (empty($google_safe_browsing_key)) {
                        return true;
                    }

                    // https://developers.google.com/safe-browsing/v4/reference/rest/v4/ClientInfo
                    $url = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$google_safe_browsing_key}";
                    $method = 'POST';
                    $data = '{
                        "client": {
                          "clientId":      "yourcompanyname",
                          "clientVersion": "1.5.2"
                        },
                        "threatInfo": {
                          "threatTypes":      ["MALWARE", "SOCIAL_ENGINEERING", "POTENTIALLY_HARMFUL_APPLICATION", ' .
                        '"UNWANTED_SOFTWARE", "MALICIOUS_BINARY"],
                          "platformTypes":    ["ANY_PLATFORM"],
                          "threatEntryTypes": ["URL"],
                          "threatEntries": [
                            {"url": "' . $value . '"},
                          ]
                        }
                      }';

                    $headers = ['Content-Type: application/json'];

                    $options = [
                        CURLOPT_CONNECTTIMEOUT => 15,
                        CURLOPT_TIMEOUT => 15,
                    ];

                    $result = @json_decode(curlRequest($url, $method, $data, $headers, $options)->body, true);

                    if (isset($result['matches'])) {
                        return false;
                    }

                    return true;
                },
                'last' => true,
                'message' => __("Google currently report this URL as an active phishing, malware, or unwanted website."),
            ])
            ->add('url', 'checkPhishtankSafeUrl', [
                'rule' => function ($value, $context) {
                    $phishtank_key = get_option('phishtank_key');

                    if (empty($phishtank_key)) {
                        return true;
                    }

                    // https://www.phishtank.com/api_info.php

                    $url = 'http://checkurl.phishtank.com/checkurl/';
                    $method = 'POST';
                    $data = [
                        'url' => $value,
                        'format' => 'json',
                        'app_key' => $phishtank_key,
                    ];

                    $options = [
                        CURLOPT_CONNECTTIMEOUT => 15,
                        CURLOPT_TIMEOUT => 15,
                    ];

                    $result = @json_decode(curlRequest($url, $method, $data, [], $options)->body, true);

                    if (isset($result['results']['valid']) && $result['results']['valid'] === true) {
                        return false;
                    }

                    return true;
                },
                'last' => true,
                'message' => __("PhishTank currently report this URL as an active phishing website."),
            ])
            ->requirePresence('alias', 'create')
            ->notBlank('alias', __('Please add an alias.'))
            ->add('alias', 'maxLength', [
                'rule' => ['maxLength', 30],
                'last' => true,
                'message' => __('Maximum alias length is 30 characters.'),
            ])
            ->add('alias', 'alphaNumericDashUnderscore', [
                'rule' => function ($value, $context) {
                    return (bool)preg_match('|^[0-9a-zA-Z_-]*$|', $value);
                },
                'last' => true,
                'message' => __('Alias can only be letters numbers, dash and underscore'),
            ])
            ->add('alias', 'checkReserved', [
                'rule' => function ($value, $context) {
                    $reserved_aliases = explode(',', get_option('reserved_aliases'));
                    $reserved_aliases = array_map('trim', $reserved_aliases);
                    $reserved_aliases = array_filter($reserved_aliases);

                    if (empty($reserved_aliases)) {
                        return true;
                    }

                    if (in_array(strtolower($value), $reserved_aliases)) {
                        return false;
                    }

                    return true;
                },
                'last' => true,
                'message' => __('This alias is a reserved word.'),
            ])
            ->add('alias', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'last' => true,
                'message' => __('Alias already exists.'),
            ])
            ->allowEmptyString('title')
            ->add('title', 'checkBannedWords', [
                'rule' => function ($value, $context) {
                    $banned_words = explode(',', get_option('links_banned_words'));
                    $banned_words = array_map('trim', $banned_words);
                    $banned_words = array_filter($banned_words);

                    if (empty($banned_words)) {
                        return true;
                    }

                    if ($this->striposArray($value, $banned_words) !== false) {
                        return false;
                    }

                    return true;
                },
                'last' => false,
                'message' => __("This link contains banned words."),
            ])
            ->allowEmptyString('description')
            ->add('description', 'checkBannedWords', [
                'rule' => function ($value, $context) {
                    $banned_words = explode(',', get_option('links_banned_words'));
                    $banned_words = array_map('trim', $banned_words);
                    $banned_words = array_filter($banned_words);

                    if (empty($banned_words)) {
                        return true;
                    }

                    if ($this->striposArray($value, $banned_words) !== false) {
                        return false;
                    }

                    return true;
                },
                'last' => true,
                'message' => __("This link contains banned words."),
            ])
            ->add('type', 'inList', [
                'rule' => ['inList', [0, 1, 2]],
                'last' => true,
                'message' => __('Choose a valid value.'),
            ])
            ->allowEmptyArray('bundles')
            ->add('bundles', 'belongToUser', [
                'rule' => function ($value, $context) {
                    $ids = (isset($value['_ids']) && is_array($value['_ids'])) ? $value['_ids'] : [];

                    $ids = array_filter($ids);

                    if (empty($ids)) {
                        return true;
                    }

                    $userBundles = $this->Bundles
                        ->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'title',
                        ])
                        ->select(['id', 'title'])
                        ->where([
                            'user_id' => $context['data']['user_id'],
                        ])
                        ->toArray();

                    foreach ($ids as $id) {
                        if (!key_exists($id, $userBundles)) {
                            return false;
                        }
                    }

                    return true;
                },
                'last' => true,
                'message' => __('Choose a valid bundle.'),
            ]);

        return $validator;
    }

    public function striposArray($haystack, $needle, $offset = 0)
    {
        if (!is_array($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $query) {
            if (stripos($haystack, $query, $offset) !== false) {
                return true; // stop on first true result
            }
        }

        return false;
    }

    public function isOwnedBy($alias, $user_id)
    {
        return $this->exists(['alias' => $alias, 'user_id' => $user_id]);
    }

    public function geturl()
    {
        do {
            $min = get_option('alias_min_length', 4);
            $max = get_option('alias_max_length', 8);

            $numAlpha = rand($min, $max);
            $out = $this->generateurl($numAlpha);
            while ($this->checkReservedAuto($out)) {
                $out = $this->generateurl($numAlpha);
            }
            $alias_count = $this->find('all')
                ->where(['alias' => $out])
                ->count();
        } while ($alias_count > 0);

        return $out;
    }

    //http://blog.justni.com/creating-a-short-url-service-using-php-and-mysql/

    public function generateurl($numAlpha)
    {
        $listAlpha = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $generateurl = '';
        $i = 0;
        while ($i < $numAlpha) {
            $random = mt_rand(0, strlen($listAlpha) - 1);
            $generateurl .= $listAlpha{$random};
            $i = $i + 1;
        }

        return $generateurl;
    }

    public function getLinkMeta($long_url)
    {
        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (parse_url($long_url, PHP_URL_SCHEME) == 'magnet') {
            return $linkMeta;
        }

        $headers = get_http_headers($long_url);

        if (isset($headers['content-type']) && stripos($headers['content-type'], 'text/html') === false) {
            return $linkMeta;
        }

        $content = curlHtmlHeadRequest($long_url, 'GET', [], [], [
            CURLOPT_ENCODING => 'gzip,deflate',
        ]);

        if (!empty($content)) {
            $doc = new \DOMDocument();
            // UTF-8 Encoding Fix
            // http://www.php.net/manual/en/domdocument.loadhtml.php#95251
            @$doc->loadHTML('<?xml encoding="UTF-8">' . $content);
            $nodes = $doc->getElementsByTagName('title');

            if (!empty($nodes->item(0)->nodeValue)) {
                $title = $nodes->item(0)->nodeValue;
                $linkMeta['title'] = $this->cleanMeta($title);
            }

            $metas = $doc->getElementsByTagName('meta');

            for ($i = 0; $i < $metas->length; $i++) {
                $meta = $metas->item($i);

                if (empty($linkMeta['description']) && $meta->getAttribute('name') == 'description') {
                    $description = $meta->getAttribute('content');
                    $linkMeta['description'] = $this->cleanMeta($description);
                }

                if (empty($linkMeta['image']) && $meta->getAttribute('property') == 'og:image') {
                    $linkMeta['image'] = $meta->getAttribute('content');
                }
            }
        }

        return $linkMeta;
    }

    public function cleanMeta($meta)
    {
        return preg_replace("/\r|\n/", "", strip_tags($meta));
    }

    public function checkReservedAuto($keyword)
    {
        //$reserved_aliases = explode( ',', Configure::read( 'Option.reserved_aliases' ) );
        $reserved_aliases = [];
        if (in_array($keyword, $reserved_aliases)) {
            return true;
        }

        return false;
    }
}
