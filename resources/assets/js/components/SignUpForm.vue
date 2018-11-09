<!--

© 2018 SwiftyCloud OÜ. All rights reserved.
Contact: info@swifty.cloud

-->

<template>
  <div class="sign-up-form" v-loading="loading">
    <el-form label-width="120px" :model="customer" :rules="rules" ref="signUpForm" @submit.native.prevent="submitForm()">
      <el-form-item label="Name:" prop="name">
        <el-input placeholder="John Smith" type="name" v-model="customer.name"></el-input>
      </el-form-item>
      <el-form-item label="Email:" prop="email" :error="emailErrorMessage" :show-message="!!emailErrorMessage">
        <el-input placeholder="Email" type="email" v-model="customer.email"></el-input>
      </el-form-item>
      <el-form-item label="Confirm email:" required :error="emailConfirmErrorMessage" :show-message="!!emailConfirmErrorMessage">
        <el-input placeholder="Email" type="email" v-model="confirmEmail"></el-input>
      </el-form-item>
      <el-form-item label="Password:" prop="password">
        <el-input placeholder="Password" type="password" v-model="customer.password"></el-input>
      </el-form-item>

      <el-form-item label label-width="0px" class="checkboxes">
        <el-checkbox label="terms_and_privacy" v-model="acceptTermsAndPrivacy">I accept the Terms of Service and Privacy Policy.</el-checkbox>
      </el-form-item>

      <el-form-item label label-width="0px" class="checkboxes">
        <el-checkbox label="receive_updates" v-model="customer.subscribed">I'd like to receive updates via email about Swifty.</el-checkbox>
      </el-form-item>

      <ul class="list-unstyled sign-up-button">
        <li><el-button native-type="submit" type="primary" :disabled="!acceptTermsAndPrivacy">Sign Up</el-button></li>
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
      confirmEmail: null,
      acceptTermsAndPrivacy: false,

      rules: {
        email: [
          { required: true, message: 'Please enter your email', trigger: 'blur' },
          { type: 'email', message: 'Please input correct email address', trigger: 'blur' }
        ],
        password: [
          { required: true, message: 'Please enter your password', trigger: 'blur' }
        ]
      },

      emailErrorMessage: '',
      emailConfirmErrorMessage: ''
    }
  },

  watch: {
    confirmEmail: function () {
      if (this.confirmEmail !== this.customer.email) {
        this.emailConfirmErrorMessage = 'Email addresses do not match'
      } else {
        this.emailConfirmErrorMessage = ''
      }
    },
    'customer.email': function () {
      if (this.confirmEmail !== this.customer.email) {
        this.emailConfirmErrorMessage = 'Email addresses do not match'
      } else {
        this.emailConfirmErrorMessage = ''
      }
    }
  },

  methods: {
    submitForm () {
      this.emailErrorMessage = ''
      this.$refs['signUpForm'].validate(valid => {
        if (valid && this.confirmEmail === this.customer.email) {
          this.loading = true
          this.customer.save().then(response => {
            this.customer.clear()
            this.confirmEmail = null
            this.emailConfirmErrorMessage = ''

            this.$router.push({ name: 'signup-success' })
          }).catch(e => {
            let data = e.response.response.data

            if ('errors' in data && 'email' in data.errors) {
              this.emailErrorMessage = data.errors.email[0]
            } else {
              this.$notify.error({
                title: 'Error',
                message: 'Something wrong. Please try again.'
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

<style lang="scss">
.checkboxes {
  text-align: center;
  margin-bottom: 0;

  &:last-of-type {
    margin-bottom: 20px;
  }

  .el-checkbox + .el-checkbox {
    margin-left: 0;
  }
}
</style>
