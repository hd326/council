
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
require('./bootstrap');

let authorizations = require('./authorizations');

Vue.prototype.authorize = function (...params) {
    // Additional admin priveledges here
    //let user = window.App.user;
    if (! window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);

    //if (!user) return false;
    //return handler(user);

    //return user ? handler(user) : false;
};

Vue.prototype.signedIn = window.App.signedIn;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));
Vue.component('new-reply', require('./components/NewReply.vue'));
Vue.component('reply', require('./components/Reply.vue'));
Vue.component('replies', require('./components/Replies.vue'));
Vue.component('favorite', require('./components/Favorite.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));
Vue.component('subscribe-button', require('./components/SubscribeButton.vue'));
Vue.component('user-notifications', require('./components/UserNotifications.vue'));
Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('wysiwyg', require('./components/Wysiwyg.vue'));
Vue.component('html-renderer', require('./components/HtmlRenderer.vue'));



const app = new Vue({
    el: '#app'
});
