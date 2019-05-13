/**
 * @package    DD_SocialSahre
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

; var DD_SocialShare = (function($, document, undefined) {

    var init = function() {

        // SocialShare
        function socialShare(event, desc, title, image, url) {

            var id  = $('#jform_id').val(),
                cid = $('#jform_content_id').val();

            $('#' + event + 'Share .text').html(Joomla.JText._('COM_DD_SOCIALSHARE_BUTTON_SHARE_WAIT'));

            $.ajax({
                method: "POST",
                url: "index.php?option=com_dd_socialshare&view=article&format=json",
                data: { event: event, id: id, cid: cid, desc: desc, title: title, image: image, url: url }
            })
                .done(function( data ) {

                    data = JSON.parse(data);

                    if(data.success === 'true')
                    {
                        $('#jform_id').attr('value', data.id);
                        $('#jform_content_id').attr('value', data.content_id);
                        $('#jform_' + event).attr('value', data.date);
                        $('.' + event + '-success').removeClass('hide');

                        $('#' + event + 'Share')
                            .removeClass('btn-success btn-large btn-' + event)
                            .addClass('btn-danger btn-small');

                        $('#' + event + 'Share .text').html(Joomla.JText._('COM_DD_SOCIALSHARE_BUTTON_SHARE_AGAIN'));
                    }
                    else
                    {
                        // todo: Add a Joomla System Message by JS and Add A JText Language string ;)
                        alert('API Error, check the component parameter and your ' + event + ' API settings\nConsole log is enabled.');
                        console.log(data);

                        $('#' + event + 'Share .text').html(Joomla.JText._('COM_DD_SOCIALSHARE_BUTTON_SHARE_NOW'));
                    }

                })
                .fail(function() {
                    alert( "error" );
                })
        }

        // twitterShare
        $('#twitterShare').on('click', function () {

            var event = 'twitter',
                desc  = $('#jform_twitter_post_text').val()
            ;

            socialShare(event, desc);

        });

        // facebookShare
        $('#facebookShare').on('click', function () {

            var event = 'facebook',
                desc  = $('#jform_facebook_post_text').val(),
                title = $('#jform_facebook_post_title').val(),
                image = $('#jform_facebook_post_image').val(),
                url   = $('#jform_facebook_post_url').val()
            ;

            socialShare(event, desc, title, image, url);

        });

    };

    // more methods

    // init public method
    return {
        init:init
    };

}(jQuery, document, undefined));

(function($) {
    $(function()
    {
        DD_SocialShare.init();
    });
})(jQuery);