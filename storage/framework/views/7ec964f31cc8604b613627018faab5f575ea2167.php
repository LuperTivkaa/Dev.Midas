<?php $__env->startSection('main-content'); ?>
<div class="container">
    
    <div class="row">
        <div class="col s12 subject-header">
            <p class="teal-text">CHANGE PASSWORD</p>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card-panel white">

                <form class="" method="POST" action="/signin">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="payment_number" name="payment_number" type="text" class="validate" required>
                            <label for="payment_number">Payment ID</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="password" name="password" type="password" class="validate" required>
                            <label for="password">New Password</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="validate" required>
                            <label for="password_confirmation">Confirm Password</label>
                        </div>
                    </div>
                    <div class="row center-align">
                        <div class="col s12 m8 offset-m2 l6 offset-l3">
                            <button type="submit"
                                class="waves-effect waves-light waves-red btn-small red darken-4">Change
                                Password</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Layouts.admin-app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>