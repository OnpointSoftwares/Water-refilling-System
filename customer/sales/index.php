<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Sales</h3>
		<div class="card-tools">
			<a href="?page=sales/manage_sales" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped" id="indi-list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>ID</th>
						<th>Date Created</th>
						<th>Customer</th>
						<th>Type</th>
						<th>Status</th>
						<th>Total Items</th>
						<th>Amount</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$user=$_SESSION['userdata']['firstname'];
						$qry = $conn->query("SELECT * from `sales` t where customer_name='$user' order by unix_timestamp(date_created) desc ");
						while($row = $qry->fetch_assoc()):
							$item_count = $conn->query("SELECT sum(quantity) as total FROM sales_items where sales_id = '{$row['id']}' ")->fetch_array()['total'];
					?>
					
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td id="id"> <?php echo $row['id'];?></td>
							<td><?php echo $row['date_created'] ?></td>
							<td><?php echo $row['customer_name'] ?></td>
							<td><?php echo ($row['type'] == 1)? "walk-in" : "for delivery" ?>
							<td class="text-center"><?php echo ($row['status'] == 1)? "<span class='badge badge-success'>Paid</span>" : "<span class='badge badge-primary'>Unpaid</span>" ?>
							<td class='text-right'><?php echo number_format($item_count) ?></td>
							<td class='text-right'><?php echo number_format($row['amount'],2) ?></td>
							<td align="center">
				                   <div class="form-group col-sm-6">
                        <label class="control-label">Delivery</label>
                        <?php $delivery=$row['delivery']; ?>
                        <select  id="delivery" name="type" class="custom-select select2">
                        	<option>None Selected</option>
                            <option value="delivered" <?php echo isset($delivery) && $delivery == 'delivered' ? "selected" : "" ?>>Delivered</option>
                            <option value="undelivered" <?php echo isset($delivery) && $delivery == 'undelivered' ? "selected" : "" ?>>UnDelivered</option>
                        </select>
                        <input type="text" name="id" id="deliveryId" value="<?php echo $row['id']; ?>" hidden><br>
                        <button class="btn btn-primary" id="update" style="height: 40px;width:70px;font-size:10px">Update Delivery</button>
                    </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	var indiList;
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Sales Transaction ?","delete_sales",[$(this).attr('data-id')])
		})
		$('#update').click(
			function(){
				_conf("Are you sure to update the delivery Status?","update_sales",[$(this).attr('data-id')])
			})
	})
	function delete_sales($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_sales",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
		function update_sales($id){
			var salesId=document.getElementById("deliveryId").value;
		var update=document.getElementById("delivery").value;
		$.ajax({
			url:_base_url_+"classes/Master.php?f=update_sales",
			method:"POST",
			data:{id: salesId,data:update},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
				}
			}
		})
	}
	$(function(){
		$('#indi-list').dataTable()
	})
</script>