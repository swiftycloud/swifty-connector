/*
  © 2018 SwiftyCloud OÜ. All rights reserved.
  Contact: info@swifty.cloud
*/

import Vue from 'vue'
import Router from 'vue-router'

import SignInUp from './pages/SignInUp'
import PasswordLink from './pages/PasswordLink'
import PasswordReset from './pages/PasswordReset'
import SignUpSuccess from './pages/SignUpSuccess'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    { path: '', redirect: '/signin' },
    { path: '/signin', name: 'signin', component: SignInUp },
    { path: '/signup', name: 'signup', component: SignInUp },
    { path: '/password/link', name: 'password-link', component: PasswordLink },
    { path: '/password/reset/:hash', name: 'password-reset', component: PasswordReset },
    { path: '/signup/success', name: 'signup-success', component: SignUpSuccess }
  ]
})