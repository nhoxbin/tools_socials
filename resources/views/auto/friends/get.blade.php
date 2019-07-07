@extends('master')
@section('page', 'Lọc Friend')
@section('page-content')
	<div class="row">
		<div class="col-md-5 col-sm-12 col-xs-12">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">User</span>
					<input type="text" id="social" class="form-control" p_uid="{{ $social->provider_uid }}" value="{{ $social->name }}" disabled>
					<div class="input-group-btn">
						<button class="btn btn-info" id="get_friends">GET</button>
					</div>
				</div>
			</div>
		</div>

		@if(isset($friends_data))
			<div class="col-md-7 col-sm-12 col-xs-12">
				<div class="row"><div class="col-md-3 col-sm-6 col-xs-12">
						<div class="form-group">
							<button id="check_all" class="btn btn-warning btn-block">Check ALL</button>
						</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="form-group">
							<button id="uncheck_all" class="btn btn-success btn-block">UnCheck ALL</button>
						</div>
					</div>

					<div class="clearfix visible-sm-block"></div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="form-group">
							<button id="unfriend" class="btn btn-danger btn-block">Unfriend</button>
						</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="form-group">
							<button id="load_all_friend" class="btn btn-primary btn-block">#</button>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
	
	@if(session('error') || session('success'))
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-{{ (session('success') ? 'success' : 'warning') }} alert-dismissable fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-{{ (session('success') ? 'check' : 'warning') }}"></i> Thông báo!</h4>
					{!! session('success') ?? session('error') !!}
				</div>
			</div>
		</div>
	@endif

	@if(isset($friends_data))
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Bạn bè của <label class="control-label" for="user">{{ $user['name'] }}</label></h3>
						<div class="box-tool">
							<span data-toggle="tooltip" class="badge bg-light-blue">
								Tổng {{ $total_count }} Bạn
							</span>
							<span data-toggle="tooltip" class="badge bg-secondary">
								Lấy được {{ count($friends_data) }} Bạn
							</span>
							<span data-toggle="tooltip" class="badge bg-red">
								{{ $total_count - count($friends_data) }} thằng bị khóa acc
							</span>
							<span data-toggle="tooltip" class="badge bg-orange">
								<span id="total_check">0</span> Người trong danh sách unfriend
							</span>
						</div>
					</div>
					<div class="box-body" id="friends">
						@foreach($friends_data as $value)
							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="box box-widget widget-user">
									<div class="widget-user-header bg-aqua-active">
										<h3 class="widget-user-username"><b>{{ $value['name'] }}</b></h3>
										<h5 class="widget-user-desc">{{ $value['id'] }}</h5>
									</div>
									<div class="widget-user-image">
										<img class="img-circle" src="{{ $value['picture'] }}" alt="User Avatar">
									</div>
									<div class="box-footer">
										<div class="row">
											<div class="col-sm-4 border-right">
												<div class="description-block">
													<h5 class="description-header"><a href="https://fb.com/{{ $value['id'] }}" class="btn btn-primary" target="_blank"><i class="fa fa-link"></i></a></h5>
													<span class="description-text">LINK TO FACEBOOK</span>
												</div>
											</div>
											<div class="col-sm-4 border-right">
												<div class="description-block">
													<h5 class="description-header">
														<div class="form-group">
															<label>
																<input type="checkbox" class="minimal-red" name="list_friend[]" value="{{ $value['id'] }}">
															</label>
														</div>
													</h5>
													<span class="description-text">CHECK TO UNFRIEND</span>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="description-block">
												<h5 class="description-header">
													<button class="btn btn-danger" onclick='alert("Comming Soon");'><i class="fa fa-user-times"></i></button>
												</h5>
												<span class="description-text">UNFRIEND</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
					<div class="box-footer">
						<h1 class="text-center">Hết rồi</h1>
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection
@push('script')
	<script>
		$('#get_friends').one('click', function() {
			$(this).attr('disabled', 'disabled');
			let uid = $('#social').attr('p_uid');
			location.href = '{{ route('fb.friends.get') }}/' + uid;
		});

		let checkboxes = $('input[name=\'list_friend[]\']');
		let check_all = $('#check_all');
		let uncheck_all = $('#uncheck_all');

		checkboxes.iCheck({
			checkboxClass: 'icheckbox_minimal-red icheckbox_square-red',
			increaseArea: '20%'
		});

		check_all.click(function() {
			if (confirm('Hãy cẩn thận nhé :)') == false) {
				alert('Chắc ấn nhầm thôi mà :)))');
				return false;
			}
			
			$.each(checkboxes, (k, v) => {
				let checked = $(v).parent().hasClass('checked');
				if (!checked) {
					$(v).iCheck('check');
				}
			});
		});

		uncheck_all.click(function() {
			Object.keys(checkboxes).map((k) => {
				let v = checkboxes[k];
				let checked = $(v).parent().hasClass('checked');
				if (checked) {
					$(v).iCheck('uncheck');
				}
			});
		});

		checkboxes.on('ifChanged', function() {
			$('#total_check').html($('input[name=\'list_friend[]\']:checked').length);
		});

		$('#unfriend').click(function() {
			let friends_checked = $('input[name="list_friend[]"]:checked');
			if (friends_checked.length == 0) {
				alert('Bạn chưa chọn người nào để unfriend -_-');
				return false;
			}
			$(this).addClass('disabled');
			$(this).attr('disabled', 'disabled');
			$('#friends').after('<div class="overlay"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');

			// sleep_loop(val, i, time, callback)
			sleep_loop(friends_checked, 0, 2e3, (v, k) => {
				let url = '{{ isset($user['provider_uid']) ? route('fb.friends.unfriend', $user['provider_uid']) : null }}';
				$.post(url, { uid: v[k].value }, (response) => {
					if (response.status === true) {
						if (k == friends_checked.length - 1) {
							setTimeout(function() {
								alert('Đã xử lý những nạn nhân xấu số :))');
								location.reload();
							}, 1e3);
						}
						console.log('Nạn nhân xấu số:', response.id_deleted);
					} else {
						alert(response.messages);
						location.reload();
					}
				});
			});
		});
	</script>
@endpush
@push('lib-css')
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="{{ asset('libs/AdminLTE-2.4.10/plugins/iCheck/all.css') }}">
@endpush
@push('lib-scripts')
	<!-- iCheck 1.0.1 -->
	<script src="{{ asset('libs/AdminLTE-2.4.10/plugins/iCheck/icheck.min.js') }}"></script>
@endpush