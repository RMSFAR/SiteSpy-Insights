DROP TABLE IF EXISTS `add_ons`;
CREATE TABLE `add_ons` (
  `id` int(11) NOT NULL,
  `add_on_name` varchar(255) NOT NULL,
  `unique_name` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `installed_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  `purchase_code` varchar(100) NOT NULL,
  `module_folder_name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ad_config`;
CREATE TABLE `ad_config` (
  `id` int(11) NOT NULL,
  `section1_html` longtext,
  `section1_html_mobile` longtext,
  `section2_html` longtext,
  `section3_html` longtext,
  `section4_html` longtext,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `alexa_info`;
CREATE TABLE `alexa_info` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `reach_rank` varchar(150) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `country_rank` varchar(150) DEFAULT NULL,
  `traffic_rank` varchar(150) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `checked_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `alexa_info_full`;
CREATE TABLE `alexa_info_full` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `alexa_rank` varchar(150) DEFAULT NULL COMMENT 'alexa_info',
  `card_geography_country` varchar(150) DEFAULT NULL COMMENT 'alexa_info',
  `bounce_rate` varchar(255) DEFAULT NULL COMMENT 'alexa_info',
  `alexa_rank_spend_time` varchar(255) DEFAULT NULL COMMENT 'alexa_info',
  `site_search_traffic` varchar(255) NOT NULL,
  `total_sites_linking_in` varchar(255) NOT NULL,
  `total_keyword_opportunities_breakdown` varchar(255) NOT NULL,
  `keyword_opportunitites_values` text NOT NULL,
  `similar_sites` text NOT NULL,
  `similar_site_overlap` text NOT NULL,
  `keyword_top` text NOT NULL,
  `top_keywords` text NOT NULL,
  `search_traffic` text NOT NULL,
  `share_voice` text NOT NULL,
  `keyword_gaps` text NOT NULL,
  `keyword_gaps_trafic_competitor` text NOT NULL,
  `keyword_gaps_search_popularity` text NOT NULL,
  `easyto_rank_keyword` text NOT NULL,
  `easyto_rank_relevence` text NOT NULL,
  `easyto_rank_search_popularity` text NOT NULL,
  `buyer_keyword` text NOT NULL,
  `buyer_keyword_traffic_to_competitor` text NOT NULL,
  `buyer_keyword_organic_competitor` text NOT NULL,
  `optimization_opportunities` text NOT NULL,
  `optimization_opportunities_search_popularity` text NOT NULL,
  `optimization_opportunities_organic_share_of_voice` text NOT NULL,
  `refferal_sites` text NOT NULL,
  `refferal_sites_links` text NOT NULL,
  `top_keywords_search_traficc` text NOT NULL,
  `top_keywords_share_of_voice` text NOT NULL,
  `site_overlap_score` text NOT NULL,
  `similar_to_this_sites` text NOT NULL,
  `similar_to_this_sites_alexa_rank` text NOT NULL,
  `card_geography_countryPercent` text NOT NULL,
  `site_metrics` text NOT NULL,
  `site_metrics_domains` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 means all',
  `is_seen` enum('0','1') NOT NULL DEFAULT '0',
  `seen_by` text COMMENT 'if user_id = 0 then comma seperated user_ids',
  `last_seen_at` datetime DEFAULT NULL,
  `color_class` varchar(50) NOT NULL DEFAULT 'primary',
  `icon` varchar(50) NOT NULL DEFAULT 'fas fa-bell',
  `status` enum('published','draft') NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `antivirus_scan_info`;
CREATE TABLE `antivirus_scan_info` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `google_status` varchar(100) DEFAULT NULL,
  `macafee_status` varchar(100) DEFAULT NULL,
  `norton_status` varchar(100) DEFAULT NULL,
  `scanned_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `autoposting`;
CREATE TABLE `autoposting` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feed_name` varchar(255) NOT NULL,
  `feed_type` enum('rss','youtube','twitter') NOT NULL DEFAULT 'rss',
  `feed_url` tinytext NOT NULL,
  `youtube_channel_id` varchar(255) NOT NULL,
  `page_ids` text NOT NULL COMMENT 'auto ids',
  `page_names` text NOT NULL COMMENT 'page names',
  `facebook_rx_fb_user_info_ids` text NOT NULL COMMENT 'page id => fb rx user id json',
  `posting_start_time` varchar(50) NOT NULL,
  `posting_end_time` varchar(50) NOT NULL,
  `posting_timezone` varchar(250) NOT NULL,
  `page_id` int(11) NOT NULL COMMENT 'broadcast',
  `fb_page_id` varchar(200) NOT NULL COMMENT 'broadcast',
  `page_name` varchar(255) NOT NULL COMMENT 'broadcast',
  `label_ids` text NOT NULL COMMENT 'broadcast',
  `excluded_label_ids` text NOT NULL COMMENT 'broadcast',
  `broadcast_start_time` varchar(50) NOT NULL,
  `broadcast_end_time` varchar(50) NOT NULL,
  `broadcast_timezone` varchar(250) NOT NULL,
  `broadcast_notification_type` varchar(100) NOT NULL DEFAULT 'REGULAR',
  `broadcast_display_unsubscribe` enum('0','1') NOT NULL DEFAULT '0',
  `last_pub_date` datetime NOT NULL,
  `last_pub_title` tinytext NOT NULL,
  `last_pub_url` tinytext NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT 'pending, processing, abandoned',
  `last_updated_at` datetime NOT NULL,
  `cron_status` enum('0','1') NOT NULL DEFAULT '0',
  `error_message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `backlink_generator`;
CREATE TABLE `backlink_generator` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `response_code` varchar(50) DEFAULT NULL,
  `status` enum('successful','failed') NOT NULL DEFAULT 'successful',
  `user_id` int(11) NOT NULL,
  `generated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `backlink_search`;
CREATE TABLE `backlink_search` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `backlink_count` varchar(100) NOT NULL,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bitly_url_shortener`;
CREATE TABLE `bitly_url_shortener` (
  `id` int(11) NOT NULL,
  `long_url` text,
  `short_url` text,
  `short_url_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `comparision`;
CREATE TABLE `comparision` (
  `id` int(11) NOT NULL,
  `base_site` int(11) NOT NULL DEFAULT '0',
  `competutor_site` int(11) NOT NULL DEFAULT '0',
  `email` longtext NOT NULL,
  `searched_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `google_safety_api` text,
  `moz_access_id` varchar(100) DEFAULT NULL,
  `moz_secret_key` varchar(100) DEFAULT NULL,
  `mobile_ready_api_key` varchar(100) NOT NULL,
  `virus_total_api` varchar(255) NOT NULL,
  `bitly_access_token` varchar(255) NOT NULL,
  `rebrandly_api_key` varchar(255) NOT NULL,
  `facebook_app_id` varchar(255) NOT NULL,
  `facebook_app_secret` varchar(255) NOT NULL,
  `access` enum('only_me','all_users') NOT NULL DEFAULT 'only_me'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `config_proxy`;
CREATE TABLE `config_proxy` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `proxy` varchar(100) DEFAULT NULL,
  `port` varchar(20) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `admin_permission` varchar(100) NOT NULL DEFAULT 'only me',
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `email_config`;
CREATE TABLE `email_config` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_type` enum('Default','tls','ssl') NOT NULL DEFAULT 'Default',
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `email_template_management`;
CREATE TABLE `email_template_management` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `template_type` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fas fa-folder-open',
  `tooltip` text NOT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `email_template_management` (`id`, `title`, `template_type`, `subject`, `message`, `icon`, `tooltip`, `info`) VALUES
(1, 'Signup Activation', 'signup_activation', '#APP_NAME# | Account Activation', '<p>To activate your account please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #ACTIVATION_URL#</li>\r\n<li>Enter this code : #ACCOUNT_ACTIVATION_CODE#</li>\r\n<li>Activate your account</li>\r\n</ol>', 'fas fa-skating', '#APP_NAME#,#ACTIVATION_URL#,#ACCOUNT_ACTIVATION_CODE#', 'When a new user open an account'),
(2, 'Reset Password', 'reset_password', '#APP_NAME# | Password Recovery', '<p>To reset your password please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #PASSWORD_RESET_URL#</li>\r\n<li>Enter this code : #PASSWORD_RESET_CODE#</li>\r\n<li>reset your password.</li>\r\n</ol>\r\n<h4>Link and code will be expired after 24 hours.</h4>', 'fas fa-retweet', '#APP_NAME#,#PASSWORD_RESET_URL#,#PASSWORD_RESET_CODE#', 'When a user forget login password'),
(3, 'Change Password', 'change_password', 'Change Password Notification', 'Dear #USERNAME#,<br/> \r\nYour <a href="#APP_URL#">#APP_NAME#</a> password has been changed.<br>\r\nYour new password is: #NEW_PASSWORD#.<br/><br/> \r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-key', '#APP_NAME#,#APP_URL#,#USERNAME#,#NEW_PASSWORD#', 'When admin reset password of any user'),
(4, 'Subscription Expiring Soon', 'membership_expiration_10_days_before', 'Payment Alert', 'Dear #USERNAME#,\r\n<br/> Your account will expire after 10 days, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', '10 days before user subscription expires'),
(5, 'Subscription Expiring Tomorrow', 'membership_expiration_1_day_before', 'Payment Alert', 'Dear #USERNAME#,<br/>\r\nYour account will expire tomorrow, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-stopwatch', '#APP_NAME#,#APP_URL#,#USERNAME#', '1 day before user subscription expires'),
(6, 'Subscription Expired', 'membership_expiration_1_day_after', 'Subscription Expired', 'Dear #USERNAME#,<br/>\r\nYour account has been expired, Please pay your fees for continuity.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-user-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', 'Subscription is already expired of a user'),
(7, 'Paypal Payment Confirmation', 'paypal_payment', 'Payment Confirmation', 'Congratulations,<br/> \r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href="#SITE_URL#">#APP_NAME#</a> Team', 'fab fa-paypal', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Paypal & gets confirmation'),
(8, 'Paypal New Payment', 'paypal_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-paypal', '#PAID_USER_NAME#', 'User pay through Paypal & admin gets notified'),
(9, 'Stripe Payment Confirmation', 'stripe_payment', 'Payment Confirmation', 'Congratulations,<br/>\r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #APP_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fab fa-stripe-s', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Stripe & gets confirmation'),
(10, 'Stripe New Payment', 'stripe_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-stripe', '#PAID_USER_NAME#', 'User pay through Stripe & admin gets notified');


DROP TABLE IF EXISTS `expired_domain_list`;
CREATE TABLE `expired_domain_list` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `auction_type` enum('pre_release','pending_delete','public_auction') CHARACTER SET latin1 DEFAULT NULL,
  `auction_end_date` datetime DEFAULT NULL,
  `sync_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `facebook_rx_config`;
CREATE TABLE `facebook_rx_config` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `app_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_secret` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `fb_simple_support_desk`;
CREATE TABLE `fb_simple_support_desk` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_title` text NOT NULL,
  `ticket_text` longtext NOT NULL,
  `ticket_status` enum('1','2','3') CHARACTER SET latin1 NOT NULL DEFAULT '1' COMMENT '1=> Open. 2 => Closed, 3 => Resolved',
  `display` enum('0','1') NOT NULL DEFAULT '1',
  `support_category` int(11) NOT NULL,
  `last_replied_by` int(11) DEFAULT NULL,
  `last_replied_at` datetime DEFAULT NULL,
  `last_action_at` datetime DEFAULT NULL COMMENT 'close resolve reopen etc',
  `ticket_open_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `fb_support_category`;
CREATE TABLE `fb_support_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `fb_support_category` (`id`, `category_name`, `user_id`, `deleted`) VALUES
(1, 'Billing', 1, '0'),
(2, 'Technical', 1, '0'),
(3, 'Query', 1, '0');


DROP TABLE IF EXISTS `fb_support_desk_reply`;
CREATE TABLE `fb_support_desk_reply` (
  `id` int(11) NOT NULL,
  `ticket_reply_text` longtext NOT NULL,
  `ticket_reply_time` datetime NOT NULL,
  `reply_id` int(11) NOT NULL COMMENT 'ticket_id',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `forget_password`;
CREATE TABLE `forget_password` (
  `id` int(11) NOT NULL,
  `confirmation_code` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ip_domain_info`;
CREATE TABLE `ip_domain_info` (
  `id` int(11) NOT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `isp` varchar(100) DEFAULT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `domain_name` varchar(250) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `time_zone` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ip_same_site`;
CREATE TABLE `ip_same_site` (
  `id` int(11) NOT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `website` longtext,
  `user_id` int(11) NOT NULL,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ip_v6_check`;
CREATE TABLE `ip_v6_check` (
  `id` int(11) NOT NULL,
  `domain_name` text CHARACTER SET utf8,
  `ipv6` varchar(200) DEFAULT NULL,
  `searched_at` datetime NOT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `is_ipv6_support` varchar(10) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `keyword_position`;
CREATE TABLE `keyword_position` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `keyword` varchar(250) NOT NULL,
  `location` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `proxy` text,
  `google_position` varchar(100) DEFAULT NULL,
  `google_top_site_url` longtext,
  `bing_position` varchar(100) DEFAULT NULL,
  `bing_top_site_url` text,
  `yahoo_position` varchar(100) DEFAULT NULL,
  `yahoo_top_site_url` text,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `keyword_position_report`;
CREATE TABLE `keyword_position_report` (
  `id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `google_position` varchar(100) NOT NULL,
  `bing_position` varchar(100) NOT NULL,
  `yahoo_position` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `keyword_position_set`;
CREATE TABLE `keyword_position_set` (
  `id` int(11) NOT NULL,
  `keyword` varchar(250) NOT NULL,
  `website` varchar(250) CHARACTER SET latin1 NOT NULL,
  `language` varchar(250) CHARACTER SET latin1 NOT NULL,
  `country` varchar(250) CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `last_scan_date` date NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `keyword_suggestion`;
CREATE TABLE `keyword_suggestion` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `google_suggestion` text,
  `bing_suggestion` text,
  `yahoo_suggestion` text,
  `wiki_suggestion` text,
  `amazon_suggestion` text,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `link_analysis`;
CREATE TABLE `link_analysis` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `external_link_count` varchar(50) DEFAULT NULL,
  `internal_link_count` varchar(50) DEFAULT NULL,
  `nofollow_count` varchar(50) DEFAULT NULL,
  `do_follow_count` varchar(50) DEFAULT NULL,
  `external_link` longtext,
  `internal_link` longtext,
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `login_config`;
CREATE TABLE `login_config` (
  `id` int(11) NOT NULL,
  `app_name` varchar(100) DEFAULT NULL,
  `api_key` varchar(250) DEFAULT NULL,
  `google_client_id` text,
  `google_client_secret` varchar(250) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `add_ons_id` int(11) NOT NULL,
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  `header_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`id`, `name`, `icon`, `color`, `url`, `serial`, `module_access`, `have_child`, `only_admin`, `only_member`, `add_ons_id`, `is_external`, `header_text`) VALUES
(1, 'Dashboard', 'fa fa-fire', '#ff7979', 'dashboard', 1, '', '0', '0', '0', 0, '0', ''),
(2, 'System', 'fas fa-laptop-code', '#d35400', '', 98, '', '1', '1', '0', 0, '0', 'Administration'),
(3, 'Subscription', 'fas fa-coins', '#ffa801', '', 99, '', '1', '1', '0', 0, '0', ''),
(12, 'Analysis Tools', 'fas fa-chart-bar', '#218c74', 'menu_loader/analysis_tools', 17, '1,2,3,4,5,6,7,8', '0', '0', '0', 0, '0', 'SEO Tools'),
(14, 'Utlities', 'fas fa-ellipsis-h', '#a55eea', 'menu_loader/utlities', 25, '12,13', '0', '0', '0', 0, '0', ''),
(15, 'URL Shortner', 'fas fa-cut', '#0D8BF1', 'menu_loader/url_shortner', 29, '18', '0', '0', '0', 0, '0', ''),
(16, 'Keyword Tracking', 'fas fa-map-marker-alt', '#ff3b01', 'menu_loader/keyword_position_tracking', 33, '16', '0', '0', '0', 0, '0', ''),
(17, 'Security Tools', 'fa fa-shield', '#5dca6e', 'menu_loader/security_tools', 37, '10', '0', '0', '0', 0, '0', ''),
(19, 'Code Minifier', 'fa fa-object-group', '#0D8BF1', 'menu_loader/code_minifier', 45, '17', '0', '0', '0', 0, '0', ''),
(20, 'Widgets', 'fas fa-puzzle-piece', '#7ebaeb', 'native_widgets/get_widget', 54, '14', '0', '0', '0', 0, '0', ''),
(21, 'Social Apps & APIs', 'fas fa-cog', '#d35400', 'social_apps/index', 98, '', '0', '0', '1', 0, '0', 'Settings');

DROP TABLE IF EXISTS `menu_child_1`;
CREATE TABLE `menu_child_1` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_child_1`
--

INSERT INTO `menu_child_1` (`id`, `name`, `url`, `serial`, `icon`, `module_access`, `parent_id`, `have_child`, `only_admin`, `only_member`, `is_external`) VALUES
(1, 'Settings', 'admin/settings', 1, 'fas fa-sliders-h', '', 2, '0', '1', '0', '0'),
(2, 'Social Apps & APIs', 'social_apps/index', 5, 'fas fa-hands-helping', '', 2, '0', '0', '0', '0'),
(3, 'Cron Job', 'cron_job/index', 9, 'fas fa-clipboard-list', '', 2, '0', '1', '0', '0'),
(5, 'Add-on Manager', 'addons/lists', 17, 'fas fa-plug', '', 2, '0', '0', '1', '0'),
(6, 'Check Update', 'update_system/index', 21, 'fas fa-leaf', '', 2, '0', '1', '0', '0'),
(7, 'Package Manager', 'payment/package_manager', 1, 'fas fa-shopping-bag', '', 3, '0', '1', '0', '0'),
(8, 'User Manager', 'admin/user_manager', 5, 'fas fa-users', '', 3, '0', '1', '0', '0'),
(9, 'Announcement', 'announcement/full_list', 9, 'far fa-bell', '', 3, '0', '1', '0', '0'),
(10, 'Payment Accounts', 'payment/accounts', 13, 'far fa-credit-card', '', 3, '0', '1', '0', '0'),
(11, 'Earning Summary', 'payment/earning_summary', 17, 'fas fa-tachometer-alt', '', 3, '0', '1', '0', '0'),
(12, 'Transaction Log', 'payment/transaction_log', 27, 'fas fa-history', '', 3, '0', '1', '0', '0'),
(17, 'Theme Manager', 'themes/lists', 19, 'fas fa-palette', '', 2, '0', '0', '1', '0'),
(18, 'Native API', 'native_api/index', 5, 'fas fa-home', '15', 2, '0', '0', '0', '0'),
(19, 'Language Editor', 'languages', 17, 'fas fa-language', '', 2, '0', '1', '0', '0');


DROP TABLE IF EXISTS `menu_child_2`;
CREATE TABLE `menu_child_2` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_child` int(11) NOT NULL,
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(250) DEFAULT NULL,
  `add_ons_id` int(11) NOT NULL,
  `extra_text` varchar(50) NOT NULL DEFAULT 'month',
  `limit_enabled` enum('0','1') NOT NULL DEFAULT '1',
  `bulk_limit_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `modules` (`id`, `module_name`, `add_ons_id`, `extra_text`, `limit_enabled`, `bulk_limit_enabled`, `deleted`) VALUES
(1, 'Visitor Analysis', 0, '', '1', '1', '0'),
(2, 'Website Analysis', 0, 'Month', '1', '1', '0'),
(3, 'Social Network Analysis', 0, '', '1', '1', '0'),
(4, 'Rank & Index Analysis', 0, 'Month', '1', '1', '0'),
(5, 'Domain Analysis', 0, 'Month', '1', '1', '0'),
(6, 'IP Analysis', 0, 'Month', '1', '1', '0'),
(7, 'Link Analysis', 0, 'Month', '1', '1', '0'),
(8, 'Keyword Analysis', 0, 'Month', '1', '1', '0'),
(10, 'Security Tools', 0, 'Month', '1', '1', '0'),
(12, 'Plagiarism Check', 0, 'Month', '1', '1', '0'),
(13, 'Utilities', 0, '', '1', '1', '0'),
(14, 'Native Widget', 0, '', '1', '1', '0'),
(15, 'Native API', 0, 'Month', '1', '1', '0'),
(16, 'Keyword Position Tracking', 0, '', '1', '1', '0'),
(17, 'Code Minifier', 0, '', '1', '1', '0'),
(18, 'URL Shortener', 0, 'Month', '1', '1', '0'),
(84, 'SiteDoctor', 3, '', '1', '0', '0');

DROP TABLE IF EXISTS `moz_info`;
CREATE TABLE `moz_info` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `mozrank_subdomain_normalized` varchar(150) NOT NULL,
  `mozrank_subdomain_raw` varchar(150) NOT NULL,
  `mozrank_url_normalized` varchar(150) NOT NULL,
  `mozrank_url_raw` varchar(150) NOT NULL,
  `http_status_code` varchar(150) NOT NULL,
  `domain_authority` varchar(150) NOT NULL,
  `page_authority` varchar(150) NOT NULL,
  `external_equity_links` varchar(150) NOT NULL,
  `links` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checked_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `native_api`;
CREATE TABLE `native_api` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `native_widgets`;
CREATE TABLE `native_widgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `domain_code` varchar(250) NOT NULL,
  `js_code` text NOT NULL,
  `table_name` text NOT NULL,
  `add_date` date NOT NULL,
  `dashboard` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
  `id` int(11) NOT NULL,
  `package_name` varchar(250) NOT NULL,
  `package_type` enum('subscription','team') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'subscription',
  `module_ids` varchar(250) NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `validity_extra_info` varchar(255) NOT NULL DEFAULT '1,M',
  `is_default` enum('0','1') NOT NULL DEFAULT '0',
  `product_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discount_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `visible` enum('0','1') NOT NULL DEFAULT '1',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `validity_extra_info`, `is_default`, `visible`, `highlight`, `deleted`) VALUES
(1, 'Trial', '17,5,6,8,16,7,15,14,12,4,10,84,3,18,13,1,2', '{\"17\":\"0\",\"5\":\"0\",\"6\":\"0\",\"8\":\"0\",\"16\":\"0\",\"7\":\"0\",\"15\":\"0\",\"14\":\"0\",\"12\":\"0\",\"4\":\"0\",\"10\":\"0\",\"84\":\"0\",\"3\":\"0\",\"18\":\"0\",\"13\":\"0\",\"1\":\"0\",\"2\":\"0\"}', '{\"17\":\"0\",\"5\":\"0\",\"6\":\"0\",\"8\":\"0\",\"16\":\"0\",\"7\":\"0\",\"15\":\"0\",\"14\":\"0\",\"12\":\"0\",\"4\":\"0\",\"10\":\"0\",\"84\":\"0\",\"3\":\"0\",\"18\":\"0\",\"13\":\"0\",\"1\":\"0\",\"2\":\"0\"}', 'Trial', 7, '1,W', '1', '1', '0', '0');


DROP TABLE IF EXISTS `page_status`;
CREATE TABLE `page_status` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `user_id` varchar(222) CHARACTER SET latin1 NOT NULL,
  `http_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  `status` varchar(50) CHARACTER SET latin1 NOT NULL,
  `total_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `namelookup_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `connect_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `speed_download` varchar(50) CHARACTER SET latin1 NOT NULL,
  `check_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `paypal_error_log`;
CREATE TABLE `paypal_error_log` (
  `id` int(11) NOT NULL,
  `call_time` datetime DEFAULT NULL,
  `ipn_value` text,
  `error_log` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rebrandly_url_shortener`;
CREATE TABLE `rebrandly_url_shortener` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `long_url` text NOT NULL,
  `short_url` text NOT NULL,
  `short_url_id` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `domainId` varchar(255) NOT NULL,
  `slashtag` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `search_engine_index`;
CREATE TABLE `search_engine_index` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `google_index` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `bing_index` varchar(20) DEFAULT NULL,
  `yahoo_index` varchar(20) DEFAULT NULL,
  `checked_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `site_check_report`;
CREATE TABLE `site_check_report` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(200) DEFAULT NULL,
  `searched_at` datetime DEFAULT NULL,
  `title` text,
  `description` text,
  `meta_keyword` text,
  `viewport` text,
  `h1` text,
  `h2` text,
  `h3` text,
  `h4` text,
  `h5` text,
  `h6` text,
  `noindex_by_meta_robot` varchar(10) DEFAULT NULL,
  `nofollowed_by_meta_robot` varchar(10) DEFAULT NULL,
  `keyword_one_phrase` text,
  `keyword_two_phrase` text,
  `keyword_three_phrase` text,
  `keyword_four_phrase` text,
  `total_words` varchar(10) DEFAULT NULL,
  `robot_txt_exist` varchar(5) DEFAULT NULL,
  `robot_txt_content` text,
  `sitemap_exist` int(11) DEFAULT NULL,
  `sitemap_location` text,
  `external_link_count` int(11) DEFAULT NULL,
  `internal_link_count` int(11) DEFAULT NULL,
  `nofollow_link_count` int(11) DEFAULT NULL,
  `dofollow_link_count` int(11) DEFAULT NULL,
  `external_link` text,
  `internal_link` text,
  `nofollow_internal_link` text,
  `not_seo_friendly_link` text,
  `image_without_alt_count` int(11) DEFAULT NULL,
  `image_not_alt_list` text,
  `inline_css` text,
  `internal_css` text,
  `depreciated_html_tag` text,
  `is_favicon_found` int(11) DEFAULT NULL,
  `favicon_link` text,
  `total_page_size_general` varchar(20) DEFAULT NULL,
  `page_size_gzip` varchar(20) DEFAULT NULL,
  `is_gzip_enable` int(11) DEFAULT NULL,
  `doctype` text,
  `doctype_is_exist` int(11) DEFAULT NULL,
  `nofollow_link_list` text,
  `canonical_link_list` text,
  `noindex_list` text,
  `micro_data_schema_list` longtext,
  `is_ipv6_compatiable` int(11) DEFAULT NULL,
  `ipv6` varchar(50) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `dns_report` text,
  `is_ip_canonical` int(11) DEFAULT NULL,
  `email_list` text,
  `is_url_canonicalized` int(11) DEFAULT NULL,
  `text_to_html_ratio` varchar(20) DEFAULT NULL,
  `general_curl_response` text,
  `mobile_loadingexperience_metrics` text NOT NULL,
  `mobile_originloadingexperience_metrics` text NOT NULL,
  `mobile_lighthouseresult_configsettings` text NOT NULL,
  `mobile_lighthouseresult_audits` longtext NOT NULL,
  `mobile_lighthouseresult_categories` text NOT NULL,
  `mobile_google_api_error` text NOT NULL,
  `perfomence_category` varchar(50) NOT NULL,
  `mobile_perfomence_score` double NOT NULL,
  `desktop_loadingexperience_metrics` text NOT NULL,
  `desktop_originloadingexperience_metrics` text NOT NULL,
  `desktop_lighthouseresult_configsettings` text NOT NULL,
  `desktop_lighthouseresult_audits` longtext NOT NULL,
  `desktop_lighthouseresult_categories` text NOT NULL,
  `desktop_google_api_error` text NOT NULL,
  `desktop_perfomence_score` double NOT NULL,
  `warning_count` int(11) DEFAULT NULL,
  `email` text NOT NULL,
  `domain_ip_info` text,
  `alexa_rank` text,
  `overall_score` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `completed_step_count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `social_info`;
CREATE TABLE `social_info` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` varchar(222) NOT NULL,
  `reddit_score` varchar(222) DEFAULT NULL,
  `reddit_up` varchar(222) DEFAULT NULL,
  `reddit_dowon` varchar(222) DEFAULT NULL,
  `linked_in_share` varchar(222) DEFAULT NULL,
  `buffer_share` varchar(222) DEFAULT NULL,
  `fb_like` varchar(222) DEFAULT NULL,
  `fb_share` varchar(222) DEFAULT NULL,
  `fb_comment` varchar(222) DEFAULT NULL,
  `fb_comment_plugin` varchar(255) NOT NULL,
  `google_plus_count` varchar(222) DEFAULT NULL,
  `xing_share_count` varchar(222) DEFAULT NULL,
  `pinterest_pin` varchar(255) NOT NULL,
  `search_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `payment_api_logs`;
CREATE TABLE `payment_api_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `call_time` datetime DEFAULT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_response` text COLLATE utf8mb4_unicode_ci,
  `error` mediumtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `settings_payments`;
CREATE TABLE `settings_payments` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `paypal` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `razorpay` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paystack` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mercadopago` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mollie` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sslcommerz` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `senangpay` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instamojo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instamojo_v2` text COLLATE utf8mb4_unicode_ci,
  `toyyibpay` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `xendit` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `myfatoorah` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paymaya` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `flutterwave` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `yoomoney` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cod_enabled` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `manual_payment_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `manual_payment_instruction` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `decimal_point` tinyint(4) NOT NULL,
  `thousand_comma` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `currency_position` enum('left','right') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `updated_at` datetime NOT NULL,
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaction_logs`;
CREATE TABLE `transaction_logs` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_user_id` bigint(20) UNSIGNED NOT NULL,
  `verify_status` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_email` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_at` datetime NOT NULL,
  `payment_method` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_amount` float NOT NULL,
  `cycle_start_date` date DEFAULT NULL,
  `cycle_expired_date` date DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `package_name` varchar(99) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_txn_type` varchar(99) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_url` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transaction_history_manual`;
CREATE TABLE `transaction_history_manual` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` varchar(255) NOT NULL,
  `paid_currency` char(4) NOT NULL,
  `additional_info` longtext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status` enum('0','1') DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `update_list`;
CREATE TABLE `update_list` (
  `id` int(11) NOT NULL,
  `files` text NOT NULL,
  `sql_query` text NOT NULL,
  `update_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `usage_log`;
CREATE TABLE `usage_log` (
  `id` bigint(20) NOT NULL,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(99) NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `add_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_date` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `activation_code` varchar(20) DEFAULT NULL,
  `expired_date` datetime DEFAULT NULL,
  `bot_status` enum('0','1') NOT NULL DEFAULT '1',
  `package_id` int(11) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL,
  `brand_logo` text,
  `brand_url` text,
  `vat_no` varchar(100) DEFAULT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL') DEFAULT 'USD',
  `time_zone` varchar(255) DEFAULT NULL,
  `company_email` varchar(200) DEFAULT NULL,
  `paypal_email` varchar(100) DEFAULT NULL,
  `last_payment_method` varchar(50) DEFAULT NULL,
  `paypal_subscriber_id` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subscription_enabled` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `last_login_ip` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `paypal_next_check_time` datetime DEFAULT NULL,
  `paypal_processing` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `remember_token`, `password`, `address`, `user_type`, `status`, `add_date`, `purchase_date`, `last_login_at`, `activation_code`, `expired_date`, `bot_status`, `package_id`, `deleted`, `brand_logo`, `brand_url`, `vat_no`, `currency`, `time_zone`, `company_email`, `paypal_email`, `last_payment_method`, `paypal_subscriber_id`, `subscription_data`, `subscription_enabled`, `last_login_ip`, `created_at`, `updated_at`, `paypal_next_check_time`, `paypal_processing`) VALUES
(1, 'Xerone IT', 'admin@gmail.com', '01729853645', NULL, '$2y$10$IO8oOh50SqBCgTIUUj7cRuMFJM9D3ha13ttNt5btqpe533q1nsED2', 'Holding No. 127, 1st Floor, Gonok Para', 'Admin', '1', '2019-08-25 12:00:00',NULL, '2023-10-31 03:28:22', NULL, NULL, '1', 0, '0', NUll, NULL, NULL, 'USD', 'Asia/Dhaka', NULL, '', '', NULL, NULL, '0', '', NULL, NULL, NULL, '0');


DROP TABLE IF EXISTS `user_login_info`;
CREATE TABLE `user_login_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `login_time` datetime NOT NULL,
  `login_ip` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` int(11) NOT NULL,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `virustotal`;
CREATE TABLE `virustotal` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(255) NOT NULL,
  `response_code` varchar(50) NOT NULL,
  `permalink` tinytext NOT NULL,
  `verbose_msg` varchar(255) NOT NULL,
  `positives` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `scans` longtext NOT NULL,
  `scanned_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `visitor_analysis_domain_list`;
CREATE TABLE `visitor_analysis_domain_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(200) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `js_code` text NOT NULL,
  `add_date` date NOT NULL,
  `dashboard` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `visitor_analysis_domain_list_data`;
CREATE TABLE `visitor_analysis_domain_list_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `date_time` datetime NOT NULL,
  `referrer` varchar(200) NOT NULL,
  `visit_url` text NOT NULL,
  `cookie_value` varchar(200) NOT NULL,
  `session_value` varchar(200) NOT NULL,
  `is_new` int(11) NOT NULL,
  `last_scroll_time` datetime DEFAULT NULL,
  `last_engagement_time` datetime DEFAULT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `is_live_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `website_analysis_info`;
CREATE TABLE `website_analysis_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `search_at` datetime NOT NULL,
  `screenshot` longtext,
  `domain_name` varchar(250) NOT NULL,
  `title` text,
  `similar_sites` text DEFAULT NULL,
  `h1` text COMMENT 'meta tag info',
  `h2` text COMMENT 'meta tag info',
  `h3` text COMMENT 'meta tag info',
  `h4` text COMMENT 'meta tag info',
  `h5` text COMMENT 'meta tag info',
  `h6` text COMMENT 'meta tag info',
  `blocked_by_robot_txt` varchar(20) DEFAULT NULL COMMENT 'meta tag info',
  `meta_tag_information` text COMMENT 'meta tag info',
  `blocked_by_meta_robot` varchar(20) DEFAULT NULL COMMENT 'meta tag info',
  `nofollowed_by_meta_robot` varchar(20) DEFAULT NULL COMMENT 'meta tag info',
  `one_phrase` text COMMENT 'meta tag info',
  `two_phrase` text COMMENT 'meta tag info',
  `three_phrase` text COMMENT 'meta tag info',
  `four_phrase` text COMMENT 'meta tag info',
  `total_words` int(11) NOT NULL DEFAULT '0',
  `dmoz_listed_or_not` varchar(150) DEFAULT NULL,
  `fb_total_share` varchar(150) DEFAULT NULL,
  `fb_total_like` varchar(150) DEFAULT NULL,
  `fb_total_comment` varchar(150) DEFAULT NULL,
  `google_back_link_count` varchar(150) DEFAULT NULL,
  `yahoo_back_link_count` varchar(150) DEFAULT NULL,
  `bing_back_link_count` varchar(150) DEFAULT NULL,
  `google_index_count` varchar(150) DEFAULT NULL,
  `google_page_rank` varchar(150) DEFAULT NULL,
  `bing_index_count` varchar(150) DEFAULT NULL,
  `yahoo_index_count` varchar(150) DEFAULT NULL,
  `whois_is_registered` varchar(150) DEFAULT NULL,
  `whois_tech_email` varchar(150) DEFAULT NULL,
  `whois_admin_email` varchar(150) DEFAULT NULL,
  `whois_name_servers` varchar(150) DEFAULT NULL,
  `whois_created_at` date DEFAULT NULL,
  `whois_changed_at` date DEFAULT NULL,
  `whois_expire_at` date DEFAULT NULL,
  `whois_registrar_url` varchar(150) DEFAULT NULL,
  `whois_registrant_name` varchar(150) DEFAULT NULL,
  `whois_registrant_organization` varchar(150) DEFAULT NULL,
  `whois_registrant_street` varchar(150) DEFAULT NULL,
  `whois_registrant_city` varchar(150) DEFAULT NULL,
  `whois_registrant_state` varchar(150) DEFAULT NULL,
  `whois_registrant_postal_code` varchar(150) DEFAULT NULL,
  `whois_registrant_email` varchar(150) DEFAULT NULL,
  `whois_registrant_country` varchar(150) DEFAULT NULL,
  `whois_registrant_phone` varchar(150) DEFAULT NULL,
  `whois_admin_name` varchar(150) DEFAULT NULL,
  `whois_admin_street` varchar(150) DEFAULT NULL,
  `whois_admin_city` varchar(150) DEFAULT NULL,
  `whois_admin_postal_code` varchar(150) DEFAULT NULL,
  `whois_admin_country` varchar(150) DEFAULT NULL,
  `whois_admin_phone` varchar(150) DEFAULT NULL,
  `googleplus_share_count` varchar(150) DEFAULT NULL,
  `pinterest_pin` varchar(150) DEFAULT NULL,
  `stumbleupon_total_view` varchar(150) DEFAULT NULL,
  `stumbleupon_total_comment` varchar(150) DEFAULT NULL,
  `stumbleupon_total_like` varchar(150) DEFAULT NULL,
  `stumbleupon_total_list` varchar(150) DEFAULT NULL,
  `linkedin_share_count` varchar(150) DEFAULT NULL,
  `buffer_share_count` varchar(150) DEFAULT NULL,
  `reddit_score` varchar(150) DEFAULT NULL,
  `reddit_ups` varchar(150) DEFAULT NULL,
  `reddit_downs` varchar(150) DEFAULT NULL,
  `xing_share_count` varchar(150) DEFAULT NULL,
  `moz_subdomain_normalized` varchar(150) DEFAULT NULL,
  `moz_subdomain_raw` varchar(150) DEFAULT NULL,
  `moz_url_normalized` varchar(150) DEFAULT NULL,
  `moz_url_raw` varchar(150) DEFAULT NULL,
  `moz_http_status_code` varchar(150) DEFAULT NULL,
  `moz_domain_authority` varchar(150) DEFAULT NULL,
  `moz_page_authority` varchar(150) DEFAULT NULL,
  `moz_external_equity_links` varchar(150) DEFAULT NULL,
  `moz_links` varchar(150) DEFAULT NULL,
  `ipinfo_isp` varchar(150) DEFAULT NULL,
  `ipinfo_ip` varchar(150) DEFAULT NULL,
  `ipinfo_city` varchar(150) DEFAULT NULL,
  `ipinfo_region` varchar(150) DEFAULT NULL,
  `ipinfo_country` varchar(150) DEFAULT NULL,
  `ipinfo_time_zone` varchar(150) DEFAULT NULL,
  `ipinfo_longitude` varchar(150) DEFAULT NULL,
  `ipinfo_latitude` varchar(150) DEFAULT NULL,
  `macafee_status` varchar(150) DEFAULT NULL,
  `norton_status` varchar(150) DEFAULT NULL,
  `google_safety_status` varchar(150) DEFAULT NULL,
  `avg_status` varchar(150) DEFAULT NULL,
  `loadingexperience_metrics` text,
  `originloadingexperience_metrics` text,
  `lighthouseresult_configsettings` text,
  `lighthouseresult_audits` longtext,
  `lighthouseresult_categories` text,
  `sites_in_same_ip` longtext,
  `completed_step_count` int(11) DEFAULT NULL,
  `completed_step_string` longtext,
  `screenshot_error` text,
  `google_api_error` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `website_ping`;
CREATE TABLE `website_ping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blog_name` varchar(100) DEFAULT NULL,
  `blog_url` varchar(250) DEFAULT NULL,
  `blog_url_to_ping` text,
  `blog_rss_feed_url` text,
  `ping_url` text NOT NULL,
  `response` varchar(100) NOT NULL,
  `ping_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `whois_search`;
CREATE TABLE `whois_search` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `tech_email` varchar(250) CHARACTER SET latin1 NOT NULL,
  `admin_email` varchar(250) CHARACTER SET latin1 NOT NULL,
  `registrant_email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `registrant_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `registrant_organization` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `registrant_street` text CHARACTER SET latin1 NOT NULL,
  `registrant_city` varchar(100) CHARACTER SET latin1 NOT NULL,
  `registrant_state` varchar(100) CHARACTER SET latin1 NOT NULL,
  `registrant_postal_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  `registrant_country` varchar(100) CHARACTER SET latin1 NOT NULL,
  `registrant_phone` varchar(20) CHARACTER SET latin1 NOT NULL,
  `registrar_url` text CHARACTER SET latin1 NOT NULL,
  `admin_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `admin_street` text CHARACTER SET latin1 NOT NULL,
  `admin_city` varchar(50) CHARACTER SET latin1 NOT NULL,
  `admin_postal_code` varchar(25) CHARACTER SET latin1 NOT NULL,
  `admin_country` varchar(50) CHARACTER SET latin1 NOT NULL,
  `admin_phone` varchar(25) CHARACTER SET latin1 NOT NULL,
  `is_registered` varchar(50) CHARACTER SET latin1 NOT NULL,
  `namve_servers` varchar(250) CHARACTER SET latin1 NOT NULL,
  `created_at` date NOT NULL,
  `changed_at` varchar(250) CHARACTER SET latin1 NOT NULL,
  `expire_at` varchar(250) CHARACTER SET latin1 NOT NULL,
  `scraped_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_login_info` CHANGE `login_ip` `login_ip` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users` CHANGE `last_login_ip` `last_login_ip` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `add_ons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`unique_name`);

ALTER TABLE `ad_config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `alexa_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`checked_at`,`domain_name`);

ALTER TABLE `alexa_info_full`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`searched_at`,`domain_name`);

ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `for_user_id` (`user_id`,`status`);

ALTER TABLE `antivirus_scan_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scanned_at` (`scanned_at`),
  ADD KEY `scan_info` (`user_id`,`scanned_at`,`domain_name`);

ALTER TABLE `autoposting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`,`cron_status`);

ALTER TABLE `backlink_generator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backlink_generator` (`user_id`,`generated_at`,`domain_name`);

ALTER TABLE `backlink_search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`searched_at`,`domain_name`);

ALTER TABLE `bitly_url_shortener`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `payment_api_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_user_id` (`buyer_user_id`);

ALTER TABLE `comparision`
  ADD PRIMARY KEY (`id`),
  ADD KEY `base_site` (`base_site`,`competutor_site`,`user_id`);

ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `config_proxy`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `email_config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `email_template_management`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `expired_domain_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auction_end_date` (`auction_end_date`);

ALTER TABLE `facebook_rx_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `fb_simple_support_desk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `support_category` (`support_category`);

ALTER TABLE `fb_support_category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fb_support_desk_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `forget_password`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ip_domain_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `searched_at` (`searched_at`);

ALTER TABLE `ip_same_site`
  ADD PRIMARY KEY (`id`),
  ADD KEY `searched_at` (`searched_at`);

ALTER TABLE `ip_v6_check`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `keyword_position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `searched_at` (`searched_at`);

ALTER TABLE `keyword_position_report`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `keyword_position_set`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `keyword_suggestion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `searched_at` (`searched_at`);

ALTER TABLE `link_analysis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `searched_at` (`searched_at`);

ALTER TABLE `login_config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu_child_1`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu_child_2`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `moz_info`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `native_api`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `native_widgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `package`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `page_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_date` (`check_date`);

ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);



ALTER TABLE `paypal_error_log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `rebrandly_url_shortener`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `search_engine_index`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checked_at` (`checked_at`);

ALTER TABLE `site_check_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain_name` (`domain_name`,`searched_at`,`user_id`);

ALTER TABLE `social_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`search_at`,`domain_name`);


ALTER TABLE `transaction_history_manual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thm_user_id` (`user_id`);

ALTER TABLE `update_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `usage_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`,`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_login_info`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `buyer_user_id` (`buyer_user_id`);

ALTER TABLE `settings_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_payments_user_id` (`user_id`);

ALTER TABLE `version`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `version` (`version`);

ALTER TABLE `virustotal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain_name` (`domain_name`,`scanned_at`,`user_id`);

ALTER TABLE `visitor_analysis_domain_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `visitor_analysis_domain_list_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `only_user_id` (`user_id`),
  ADD KEY `domain_id_and_date` (`domain_list_id`,`date_time`);

ALTER TABLE `website_analysis_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`domain_name`);

ALTER TABLE `website_ping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`ping_at`);

ALTER TABLE `whois_search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scraped_time` (`scraped_time`);

ALTER TABLE `add_ons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ad_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `alexa_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `alexa_info_full`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `settings_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `antivirus_scan_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `autoposting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `backlink_generator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `backlink_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `bitly_url_shortener`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `comparision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment_api_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `transaction_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `config_proxy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `email_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `email_template_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `expired_domain_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `facebook_rx_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fb_simple_support_desk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fb_support_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fb_support_desk_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `forget_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ip_domain_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ip_same_site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ip_v6_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `keyword_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `keyword_position_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `keyword_position_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `keyword_suggestion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `link_analysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `login_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `menu_child_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

ALTER TABLE `menu_child_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

ALTER TABLE `moz_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `native_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `native_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `page_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `paypal_error_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rebrandly_url_shortener`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `search_engine_index`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `site_check_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `social_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;



ALTER TABLE `transaction_history_manual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `update_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usage_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE `user_login_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `virustotal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `visitor_analysis_domain_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `visitor_analysis_domain_list_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `website_analysis_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `website_ping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `whois_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `transaction_history_manual`
  ADD CONSTRAINT `thm_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;