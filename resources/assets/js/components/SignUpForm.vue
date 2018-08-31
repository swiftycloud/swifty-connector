<!-- 

© 2018 SwiftyCloud OÜ. All rights reserved.
Contact: info@swifty.cloud

-->

<template>
  <div class="sign-up-form" v-loading="loading">
    <el-form label-width="120px" :model="customer" :rules="rules" ref="signUpForm" @submit.native.prevent="submitForm()">
      <el-form-item label="Your email:" prop="email" :error="emailErrorMessage" :show-message="!!emailErrorMessage">
        <el-input placeholder="Email" type="email" v-model="customer.email"></el-input>
      </el-form-item>
      <el-form-item label="Your name:" prop="name">
        <el-input placeholder="John Smith" type="name" v-model="customer.name"></el-input>
      </el-form-item>
      <el-form-item label="Password:" prop="password">
        <el-input placeholder="Password" type="password" v-model="customer.password"></el-input>
      </el-form-item>

      <ul class="list-unstyled sign-up-button">
        <li><el-button native-type="submit" type="primary">Sign Up</el-button></li>
      </ul>
    </el-form>
  </div>
</template>

<script>
import { Customer } from '../models/Customer'

export default {
  data () {
    return {
      loading: false,
      customer: new Customer,

      rules: {
        email: [
          { required: true, message: 'Please enter your email', trigger: 'blur' },
          { type: 'email', message: 'Please input correct email address', trigger: 'blur' }
        ],
        password: [
          { required: true, message: 'Please enter your password', trigger: 'blur' }
        ]
      },

      emailErrorMessage: ''
    }
  },

  methods: {
    submitForm () {
      this.loading = true
      this.emailErrorMessage = ''
      this.$refs['signUpForm'].validate(valid => {
        if (valid) {
          this.customer.save().then(response => {
            this.customer.clear()

            this.$alert('Please check your email for continue registration', 'Last step!', {
              confirmButtonText: 'OK',
              type: 'success',
              center: true
            })
          }).catch(e => {
            let data = e.response.response.data

            if ('errors' in data && 'email' in data.errors) {
              this.emailErrorMessage = data.errors.email[0]
            } else {
              this.$notify.error({
                title: 'Error',
                message: 'Something wrong. Try again.'
              });
            }
          }).finally(() => {
            this.loading = false
          })
        }
      })
    }
  }
}
</script>