<?php
defined( 'ABSPATH' ) || exit;
// Get employees table data
$employees = Hrm_DbQuery::get_table_data('employees');
$clients = Hrm_DbQuery::get_table_data('clients');
$projects = Hrm_DbQuery::get_table_data('projects');
$invoices = Hrm_DbQuery::get_table_data('invoices');
?>

<div class="row">
    <!--Employees table-->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-header text-center">
                <h5><?php echo esc_html__('Latest Employees', 'cm-hrm')?></h5>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Company</th>
                            <th scope="col">Department</th>
                            <th scope="col">Designation</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        foreach( $employees as $key => $employee ) : ?>
                            <?php if ( $count < 5 ) : ?>
                                <tr>
                                    <th scope="row"><?php echo $key+1 ?></th>
                                    <td><span><?php echo $employee->name ?></span></td>
                                    <td><span><?php echo Hrm_DbQuery::get_table_field_value('company', 'name', $employee->company_id) ?></span></td>
                                    <td><span><?php echo Hrm_DbQuery::get_table_field_value('departments', 'name', $employee->department_id) ?></span></td>
                                    <td><span><?php echo Hrm_DbQuery::get_table_field_value('designation', 'name', $employee->designation_id) ?></span></td>
                                </tr>
                            <?php endif; ?>
                            <?php $count++; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Clients table-->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-header text-center">
                <h5><?php echo esc_html__('Latest Clients', 'cm-hrm')?></h5>
            </div>
            <div class="card-body p-2 table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$count = 0;
					foreach( $clients as $key => $client ) : ?>
						<?php if ( $count < 5 ) : ?>
                            <tr>
                                <th scope="row"><?php echo $key+1 ?></th>
                                <td><span><?php echo $client->name ?></span></td>
                                <td><span><?php echo $client->email ?></span></td>
                                <td><span><?php echo $client->phone ?></span></td>
                            </tr>
						<?php endif; ?>
						<?php $count++; ?>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Projects table-->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-header text-center">
                <h5><?php echo esc_html__('Latest Projects', 'cm-hrm')?></h5>
            </div>
            <div class="card-body p-2 table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Project</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Finish Date</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$count = 0;
					foreach( $projects as $key => $project ) : ?>
						<?php if ( $count < 5 ) : ?>
                            <tr>
                                <th scope="row"><?php echo $key+1 ?></th>
                                <td><span><?php echo $project->title ?></span></td>
                                <td><span><?php echo $project->start_date ?></span></td>
                                <td><span><?php echo $project->finish_date ?></span></td>
                                <td><span><?php echo esc_html__(strtoupper($project->priority), 'cm-hrm') ?></span></td>
                                <td>
		                            <?php if ( $project->status == 'not_started' ) : ?>
                                        <span class="badge bg-dark"><?php echo esc_html__('Not Started', 'cm-hrm') ?></span>
		                            <?php elseif ( $project->status == 'in_progress' ) : ?>
                                        <span class="badge bg-primary"><?php echo esc_html__('In Progress', 'cm-hrm') ?></span>
		                            <?php elseif ( $project->status == 'on_hold' ) : ?>
                                        <span class="badge bg-warning text-dark"><?php echo esc_html__('On Hold', 'cm-hrm') ?></span>
		                            <?php elseif ( $project->status == 'completed' ) : ?>
                                        <span class="badge bg-success"><?php echo esc_html__('Completed', 'cm-hrm') ?></span>
		                            <?php elseif ( $project->status == 'cancelled' ) : ?>
                                        <span class="badge bg-danger"><?php echo esc_html__('Cancelled', 'cm-hrm') ?></span>
		                            <?php endif; ?>
                                </td>
                            </tr>
						<?php endif; ?>
						<?php $count++; ?>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Invoices table-->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-header text-center">
                <h5><?php echo esc_html__('Latest Invoices', 'cm-hrm')?></h5>
            </div>
            <div class="card-body p-2 table-responsive">
                <table class="table align-middle ">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Pay To</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$count = 0;
					foreach( $invoices as $key => $invoice ) : ?>
						<?php if ( $count < 5 ) : ?>
                            <tr>
                                <th scope="row"><?php echo $key+1 ?></th>
                                <td><span><?php echo 'Inv-' .$invoice->id ?></span></td>
                                <td><span><?php echo $invoice->date ?></span></td>
                                <td><span><?php echo $invoice->pay_name ?></span></td>
                                <td><span class="badge <?php echo $invoice->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark'?>"><?php echo esc_html__($invoice->payment_status, 'cm-hrm') ?></span></td>
                            </tr>
						<?php endif; ?>
						<?php $count++; ?>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    ( function( $ ) {

    })( jQuery );
</script>