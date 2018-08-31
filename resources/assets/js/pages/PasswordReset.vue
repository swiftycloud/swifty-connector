<template>
  <div class="password-reset">
    <el-card class="password-reset-card">
      <div slot="header" class="clearfix">
        <span>Change password</span>
      </div>
      <password-reset-form/>
    </el-card>
  </div>
</template>

<script>
import PasswordResetForm from '../components/PasswordResetForm'

export default {
  components: { PasswordResetForm }
}
</script>