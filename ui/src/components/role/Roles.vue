<template>
    <div class="row">
        <my-role
            v-for="role in roles"
            @delete-role="deleteRole(role)"
            :authenticatedUser="authenticatedUser"
            :role="role">
        </my-role>
    </div>
</template>

<script>
    import Role from './Role.vue'
    import swal from 'sweetalert'

    export default{
        data(){
            return{
                roles:[],
            }
        },

        computed: {
            authenticatedUser(){
                return this.$auth.getAuthenticatedUser()
            }
        },

        components: {
            'my-role': Role
        },

        created(){
            this.$http.get('api/roles')
                .then(response => {
                    this.roles = response.body
                })
        },

        methods: {
            deleteRole(role){
                swal({   
                    title: "Are you sure?",   
                    text: "You will not be able to recover this peoduct!",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Yes, delete it!",   
                    closeOnConfirm: false 
                }, 
                function(){   
                    this.$http.delete('api/roles/' + role.id).then(response => {
                        let index = this.roles.indexOf(role);
                        this.roles.splice(index, 1)
                        swal("Deleted!", "Your Role has been deleted.", "success"); 
                    });
                }.bind(this));
            }
        }
    }
</script>