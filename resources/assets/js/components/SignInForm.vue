<!-- 

© 2018 SwiftyCloud OÜ. All rights reserved.
Contact: info@swifty.cloud

-->

<template>
  <div class="sign-in-form" v-loading="loading">
    <el-form label-width="120px" :model="form" :rules="rules" ref="signInForm" @submit.native.prevent="submitForm()">
      <el-form-item label="Your email:" prop="email">
        <el-input placeholder="Email" type="email" v-model="form.email"></el-input>
      </el-form-item>
      <el-form-item label="Password:" prop="password">
        <el-input placeholder="Password" type="password" v-model="form.password"></el-input>
      </el-form-item>

      <ul class="sign-in-button">
        <li><el-button type="primary" native-type="submit">Sign In</el-button></li>
      </ul>
    </el-form>

    <ul class="sign-in-links">
      <li><small><router-link :to="{ name: 'password-link' }">Forgot your password?</router-link></small></li>
      <li><router-link :to="{ name: 'signup' }">Don’t have an account? Sign up!</router-link></li>
    </ul>
  </div>
</template>

<script>
function setCookie(name, value, options) {
  options = options || {};

  var expires = options.expires;

  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);

  var updatedCookie = name + "=" + value;

  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];
    if (propValue !== true) {
      updatedCookie += "=" + propValue;
    }
  }

  document.cookie = updatedCookie;
}

export default {
  data () {
    return {
      loading: false,

      form: {
        email: null,
        password: null
      },
      rules: {
        email: [
          { required: true, message: 'Please enter your email', trigger: 'blur' },
          { type: 'email', message: 'Please input correct email address', trigger: 'blur' }
        ],
        password: [
          { required: true, message: 'Please enter your password', trigger: 'blur' }
        ]
      }
    }
  },

  created () {
    if ('confirmed' in this.$route.query && this.$route.query.confirmed) {
      this.$alert('Your registration is complete!', 'Done!', {
        confirmButtonText: 'Sign In',
        type: 'success',
        center: true
      }).then(() => {
        this.$router.push({ name: 'signin' })
      })
    }
  },

  methods: {
    submitForm (formName) {
      this.$refs['signInForm'].validate(valid => {
        if (valid) {
          this.loading = true
          axios.post('/api/customers/login', this.form).then(response => {
            if (response.data.status === 'ok' && response.data.token) {
              setCookie('_id', this.form.email, { domain: response.data.domain })
              setCookie('_token', response.data.token, { domain: response.data.domain })
              setCookie('_expires', response.data.expires, { domain: response.data.domain })
              setCookie('_domain', response.data.domain, { domain: response.data.domain })

              window.location.href = response.data.redirect_to
            } else if (response.data.message) {
              this.$message.error(response.data.message)
            }
          }).catch(error => {
            this.$message.error('Something was wrong... Try again')
          }).finally(() => {
            this.loading = false
          })
        }
      })
    }
  }
}
</script>
