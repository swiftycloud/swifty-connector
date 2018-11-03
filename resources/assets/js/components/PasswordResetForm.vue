<template>
  <div class="password-reset-form" v-loading="loading">
    <el-form label-width="170px" ref="submitForm" :rules="rules" :model="form" @submit.native.prevent="submitForm()">
      <el-form-item label="New password:" prop="password">
        <el-input placeholder="Password" type="password" v-model="form.password"></el-input>
      </el-form-item>
      <el-form-item label="Confirm password:" prop="confirm_password" :error="passwordConfirmErrorMessage" :show-message="!!passwordConfirmErrorMessage">
        <el-input placeholder="Password" type="password" v-model="form.confirm_password"></el-input>
      </el-form-item>

      <ul class="password-reset-button">
        <li><el-button type="primary" native-type="submit">Reset password</el-button></li>
      </ul>
    </el-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      loading: false,

      form: {
        hash: this.$route.params.hash,
        password: null,
        confirm_password: null
      },

      rules: {
        password: [
          { required: true, message: 'Please enter new password', trigger: 'blur' }
        ],
        confirm_password: [
          { required: true, message: 'Please confirm new password', trigger: 'blur' }
        ]
      },

      passwordConfirmErrorMessage: ''
    }
  },

  watch: {
    'form.confirm_password': function () {
      if (this.form.confirm_password !== this.form.password) {
        this.passwordConfirmErrorMessage = 'Passwords do not match'
      } else {
        this.passwordConfirmErrorMessage = ''
      }
    },
    'form.password': function () {
      if (this.form.confirm_password !== this.form.password) {
        this.passwordConfirmErrorMessage = 'Passwords do not match'
      } else {
        this.passwordConfirmErrorMessage = ''
      }
    }
  },

  methods: {
    submitForm () {
      this.$refs['submitForm'].validate(valid => {
        if (valid && this.form.confirm_password === this.form.password) {
          this.loading = true
          axios.put('/api/customers/password/reset', this.form).then(response => {
            if (response.data.status === 'ok') {
              this.$alert('Password changed', 'Success', {
                confirmButtonText: 'Sign In',
                type: 'success',
                center: true
              }).then(() => {
                this.$router.push({ name: 'signin' })
              })
            } else {
              this.$alert(response.data.message, 'Error', {
                confirmButtonText: 'OK',
                type: 'warning',
                center: true
              })
            }
          }).catch(() => {
            this.$alert('Something was wrong', 'Error', {
              confirmButtonText: 'OK',
              type: 'error',
              center: true
            })
          }).finally(() => {
            this.loading = false
          })
        }
      })
    }
  }
}
</script>