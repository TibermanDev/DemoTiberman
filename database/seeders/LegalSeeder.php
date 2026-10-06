<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Isi halaman Privacy Policy & Disclaimer — disalin dari situs lama.
 * Dipisah supaya bisa dijalankan sendiri (db:seed --class=LegalSeeder).
 */
class LegalSeeder extends Seeder
{
    public function run(): void
    {
        Setting::put('privacy', [
            'seo_title' => 'Privacy Policy — Tiberman',
            'seo_description' => 'Kebijakan privasi Tiberman: bagaimana kami mengumpulkan, menggunakan, dan melindungi data pengunjung situs.',
            'hero_title' => 'Privacy Policy',
            'hero_subtitle' => 'How we collect, use and protect your data',
            'sections' => [
                ['heading' => 'Who we are ?', 'body' => 'Our website address is: https://tiberman.com.'],
                ['heading' => 'Comments', 'body' => "When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor’s IP address and browser user agent string to help spam detection.\n\nAn anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment."],
                ['heading' => 'Images', 'body' => 'If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.'],
                ['heading' => 'Cookies', 'body' => "If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.\nIf you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.\nWhen you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select “Remember Me”, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.\nIf you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day."],
                ['heading' => 'Embedded content from other websites', 'body' => "Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.\nThese websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website."],
                ['heading' => 'Who we share your data with', 'body' => 'If you request a password reset, your IP address will be included in the reset email.'],
                ['heading' => 'How long we retain your data', 'body' => "If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.\nFor users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information."],
                ['heading' => 'What rights you have over your data', 'body' => 'If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.'],
                ['heading' => 'Where we send your data', 'body' => 'Visitor comments may be checked through an automated spam detection service.'],
            ],
        ]);

        Setting::put('disclaimer', [
            'seo_title' => 'Disclaimer — Tiberman',
            'seo_description' => 'Disclaimer situs Tiberman: batasan tanggung jawab atas informasi dan tautan ke situs lain.',
            'hero_title' => 'Disclaimer',
            'hero_subtitle' => '',
            'sections' => [
                ['heading' => 'Disclaimer for Tiberman', 'body' => 'If you require any more information or have any questions about our site’s disclaimer, please feel free to contact us by email at tigaberlianmandiri.online@gmail.com'],
                ['heading' => 'Disclaimers for Tiberman', 'body' => "All the information on this website – https://tiberman.com/ – is published in good faith and for general information purpose only. Tiberman does not make any warranties about the completeness, reliability and accuracy of this information. Any action you take upon the information you find on this website (Tiberman), is strictly at your own risk. Tiberman will not be liable for any losses and/or damages in connection with the use of our website. Our Disclaimer was generated with the help of the <a href=\"https://www.disclaimergenerator.net/\">Disclaimer Generator</a>.\nFrom our website, you can visit other websites by following hyperlinks to such external sites. While we strive to provide only quality links to useful and ethical websites, we have no control over the content and nature of these sites. These links to other websites do not imply a recommendation for all the content found on these sites. Site owners and content may change without notice and may occur before we have the opportunity to remove a link which may have gone ‘bad’.\nPlease be also aware that when you leave our website, other sites may have different privacy policies and terms which are beyond our control. Please be sure to check the Privacy Policies of these sites as well as their “Terms of Service” before engaging in any business or uploading any information."],
                ['heading' => 'Consent', 'body' => 'By using our website, you hereby consent to our disclaimer and agree to its terms.'],
                ['heading' => 'Update', 'body' => 'Should we update, amend or make any changes to this document, those changes will be prominently posted here.'],
            ],
        ]);
    }
}
