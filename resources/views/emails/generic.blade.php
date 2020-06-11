<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GIFMS Email Notification</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
	<table cellpadding="0" cellspacing="0" style="width: 100%; border: 0;">	
		<tr>
			<td style="padding: 10px 0 30px 0;">
				<table cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse; margin: 0 auto;">
					<tr>
						<td style="background-color:#ffffff;  padding: 0 30px; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
							<img src="{{ $message->embed('img/banner-top.png') }}" alt="Clinton Health Access Initiative logo" style="display: block; margin: 0 auto; width: 100%" />
						</td>
					</tr>
					<tr>
						<td style="padding: 40px 30px 40px 30px; background-color: #ffffff;">
							<table cellpadding="0" cellspacing="0" style="width: 100%; border: 0;">
								<tr>
									<td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
										<b>{{$title}}</b>
									</td>
								</tr>
								<tr>
									<td style="padding: 20px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										@foreach ($paragraphs as $item)
                                            {{$item}}
                                        @endforeach
									</td>
                                </tr>
                                @if (isset($signature) && !empty($signature))
                                <tr>
									<td style="padding: 20px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										{{$signature}}
									</td>
								</tr>
                                @endif	
                                @if ($js_url)
                                <tr>
									<td style="padding: 20px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										Login <a href="{{$js_url}}">here</a> to proceed
									</td>
								</tr>
                                @endif								
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 30px 30px 30px 30px; background-color: #ffff;">
							<table cellpadding="0" cellspacing="0" style="border-top:1px solid #003d79; width:100%;">
								<tr>
									<td style="color: #003d79; font-family: Arial, sans-serif; font-size: 14px; width: 75%; text-align: center; padding-top: 12px;;">
										Clinton Health Access Initiative • 3rd Floor • Timau Plaza • Argwings Kodhek Road<br/>
										P.O. Box 2011-00100 • Nairobi, Kenya • Tel: 254 20 514 3100
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>