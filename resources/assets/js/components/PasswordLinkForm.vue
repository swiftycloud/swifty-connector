<template>
  <div class="password-reset-form" v-loading="loading">
    <el-form label-width="170px" ref="submitForm" :rules="rules" :model="form" @submit.native.prevent="submitForm()">
      <el-form-item label="Your email:" prop="email">
        <el-input placeholder="Email" type="email" v-model="form.email"></el-input>
      </el-form-item>

      <ul class="password-reset-button">
        <li><el-button type="primary" native-type="submit">Send Password Reset Link</el-button></li>
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
        email: null
      },

      rules: {
        email: [
          { required: true, message: 'Please enter your email', trigger: 'blur' },
          { type: 'email', message: 'Please input correct email address', trigger: 'blur' }
        ]
      }
    }
  },

  methods: {
    submitForm () {
      this.$refs['submitForm'].validate(valid => {
        if (valid) {
          this.loading = true
          axios.post('/api/customers/password/link', this.form).then(response => {
            this.$alert('We sent the link to the entered email', 'Done!', {
              confirmButtonText: 'OK',
              type: 'success',
              center: true
            })
            this.form.email = null
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