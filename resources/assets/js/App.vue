<!--

© 2018 SwiftyCloud OÜ. All rights reserved.
Contact: info@swifty.cloud

-->

<template>
  <div id="app">
    <el-container>
      <navbar-mini></navbar-mini>
      <el-container>
        <el-main>
          <router-view/>
        </el-main>
        <el-footer height="40px">
          <el-row>
            <el-col :span="20">
              © 2018 SwiftyCloud OÜ. All rights reserved.
            </el-col>
            <el-col :span="4" style="text-align: right">
              <a href="mailto:info@swifty.cloud">Contact Us</a>
            </el-col>
          </el-row>
        </el-footer>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import NavbarMini from './components/NavbarMini'
import SignUpForm from './components/SignUpForm'
import SignInForm from './components/SignInForm'

export default {
  name: 'app',
  components: { NavbarMini, SignUpForm, SignInForm }
}
</script>
