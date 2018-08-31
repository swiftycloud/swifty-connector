import { Model, Collection } from 'vue-mc'

export class Customer extends Model {

  defaults () {
    return {
      email: '',
      password: '',
      name: ''
    }
  }

  routes () {
    return {
      fetch: '/api/customers/{id}',
      save: '/api/customers'
    }
  }

}

export class CustomerList extends Collection {

  model () {
    return Customer
  }

  routes () {
    return {
      fetch: '/api/customers'
    }
  }

}