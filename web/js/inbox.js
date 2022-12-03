let inbox = {

    interval: null,
    heights: {},

    init: function () {
        inbox.bindEvents();
    },

    bindEvents: function () {
        $(document).ready(this.documentReady);
        $(document).on('submit',          '#chatbox-form',         this.handleMessageSubmit);
        $(document).on('pjax:clicked',    '#id-conversation-list', this.handlePjaxClickedListItem)
        $(document).on('pjax:beforeSend', '#id-conversation',      this.handlePjaxBeforeConversationRefresh)
        $(document).on('pjax:complete',   '#id-conversation',      this.handlePjaxAfterConversationRefresh)
        $(document).on('pjax:complete',   '#id-conversation-list', this.handlePjaxAfterConversationListRefresh)
    },

    documentReady: function () {
        $('.contact-list').niceScroll();
        inbox.refreshInterval();
        setTimeout(() => {
            console.log($('#mychatbox > .chat-content').get(0).scrollHeight)
            $('#mychatbox > .chat-content').scrollTop($('#mychatbox > .chat-content').get(0).scrollHeight, -1);
        }, 500);
    },

    refreshInterval: function () {
        clearInterval(inbox.interval);
        inbox.interval = setInterval(() => {
            if (!$('input#chat-message-input').is(':focus')) {
                $.pjax.reload({
                    container: '#id-conversation-list'
                });
                $.pjax.reload({
                    container: '#id-conversation'
                });
            }
        }, 5000);
    },

    handlePjaxClickedListItem: function (e) {
        $.pjax.reload({
            container: '#id-conversation'
        });
    },

    handlePjaxBeforeConversationRefresh: function (e) {
        $('#group-details').data('group-id')
        inbox.heights[$('#group-details').data('group-id')] = $('#mychatbox > .chat-content').get(0).scrollTop;
        $('#mychatbox > .chat-content').getNiceScroll().remove();
    },

    handlePjaxAfterConversationListRefresh: function(){
        inbox.updateMessageIndicator();
    },

    handlePjaxAfterConversationRefresh: function (e) {
        var offset = inbox.heights[$('#group-details').data('group-id')];
        if (typeof offset == 'undefined') {
            offset = $('#mychatbox > .chat-content').get(0).scrollHeight;
            inbox.heights[$('#group-details').data('group-id')] = offset;
        }
        $('.contact-list').niceScroll();
        $('#mychatbox > .chat-content').niceScroll();
        $('#mychatbox > .chat-content').getNiceScroll().hide(-1);
        $('#mychatbox > .chat-content').scrollTop(offset, -1);
        $.pjax.reload({
            container: '#id-conversation-list'
        });
        inbox.refreshInterval();
        inbox.updateMessageIndicator();
    },

    handleMessageSubmit: function (e) {

        e.preventDefault();

        var me = $(this),
            this_text = me.find('#chat-message-input').val(),
            this_picture = $('#user-avatar').attr('src'),
            groupId = $('#group-details').data('group-id');

        var m = new Date();
        var dateString =
            m.getUTCFullYear() + "-" +
            ("0" + (m.getUTCMonth() + 1)).slice(-2) + "-" +
            ("0" + m.getUTCDate()).slice(-2) + " " +
            ("0" + m.getUTCHours()).slice(-2) + ":" +
            ("0" + m.getUTCMinutes()).slice(-2) + ":" +
            ("0" + m.getUTCSeconds()).slice(-2);


        $.ajax({
            url: $('#chatbox-form').attr('action'),
            data: {
                group_id: groupId,
                message: this_text
            },
            type: 'POST',
            success: function (data) {
                $.chatCtrl('#mychatbox', {
                    text: this_text,
                    picture: this_picture,
                    time: dateString,
                    author: 'asdasdasd',
                    onShow: function (el) {
                        let addedEl = $(document).find('.chat-box').find('.chat-right').last();
                        let str = '<div class="chat-author">' + $('#group-details').data('user') + '</div>';
                        addedEl.find('.chat-details').append(str);
                        setTimeout(() => { $('#mychatbox > .chat-content').scrollTop($('#mychatbox > .chat-content').get(0).scrollHeight, -1); }, 200);
                    }
                });
                me.find('#chat-message-input').val(null);
            },
            error: function (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message ?? 'Error',
                })
            }
        });

        return false;
    },

    updateMessageIndicator: function(){
        if($('#contact-list-container').data('has-new-messages')){
            if(!$('#messages-top-link').hasClass('beep')){
                $('#messages-top-link').addClass('beep');
            }
        } else {
            if($('#messages-top-link').hasClass('beep')){
                $('#messages-top-link').removeClass('beep');
            }
        }
    }

};

inbox.init();