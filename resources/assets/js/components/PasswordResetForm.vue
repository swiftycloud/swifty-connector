<template>
  <div class="password-reset-form" v-loading="loading">
    <el-form label-width="120px" ref="submitForm" :rules="rules" :model="form" @submit.native.prevent="submitForm()">
      <el-form-item label="New password:" prop="password">
        <el-input placeholder="Password" type="password" v-model="form.password"></el-input>
      </el-form-item>

      <ul class="password-reset-button">
        <li><el-button type="primary" native-type="submit">Confirm</el-button></li>
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
        password: null
      },

      rules: {
        password: [
          { required: true, message: 'Please enter new password', trigger: 'blur' }
        ]
      }
    }
  },

  methods: {
    submitForm () {
      this.$refs['submitForm'].validate(valid => {
        if (valid) {
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
            this.$alert('Something wrong', 'Error', {
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