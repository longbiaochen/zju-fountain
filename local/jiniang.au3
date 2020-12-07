; 2011-12-28	重构机娘本地代码
; Definition
; - light: light input: 0111000011101111
; - state: state input: 010101
; ---------------------------------

#include <GUIConstants.au3>
#include <GUIConstantsEx.au3>
#include <WindowsConstants.au3>
#include <GDIPlus.au3>
#include <IE.au3>
#include <Date.au3>
#include "Webcam.au3"

; globals
$fn_img=@ScriptDir&'\s.bmp'
$x=380
$y=200
$msg_on='on'
$msg_off='off'

$thres=700
$a_while=700 ; 7 seconds

$light=0
$new_light=0
$state=0
$new_state=0
$is_accepted=0

Opt("GUIOnEventMode", 1)
$gui = GUICreate("WebCam - Ready",640,480)
GUISetOnEvent($GUI_EVENT_CLOSE, "Close")
GUISetState(@SW_SHOW)

_WebcamInit()
_Webcam($gui,640,480,0,0)


While 1
	$new_light=get_light()

	If $new_light<>$light Then
		$new_state=$new_light
		If $new_state<>$state Then

			If $new_state==0 Then
				If is_accepted() Then
					ConsoleWrite('Sending off'&@CRLF)
					send_weibo($msg_off)
					$state=$new_state
				Else
					ConsoleWrite('State OFF filtered.'&@CRLF)
				EndIf
			Else
				ConsoleWrite('Sending on'&@CRLF)
				send_weibo($msg_on)
				$state=$new_state
			EndIf
		EndIf
	EndIf

	$light=$new_light

	Sleep($a_while)

WEnd

Func get_light()
	_WebcamSnapShot($fn_img)
	$c = _GetPixelFromBMP($fn_img, $x, $y)
	ToolTip('Light: '&$c, @DesktopWidth-150, @DesktopHeight-50)

	If $c>$thres Then
		Return 1
	Else
		Return 0
	EndIf

EndFunc

Func is_accepted()
	$is_accepted=Not $is_accepted
	Return $is_accepted

EndFunc

Func send_weibo($msg)
	ToolTip('Sending Weibo: '&$msg, @DesktopWidth-400, @DesktopHeight-50)
	; Return ; DEBUG

	WinClose('http://zjufountain.sinaapp.com')
	$url_msg='http://zjufountain.sinaapp.com/buzz.php?text='&$msg
	$ie=_IECreate($url_msg, 0, 0)
	ConsoleWrite('Done!'&@CRLF)

EndFunc


Func get_val($c)
	$r=Int('0x'&StringMid($c, 3, 2))
	$g=Int('0x'&StringMid($c, 5, 2))
	$b=Int('0x'&StringMid($c, 7, 2))
	$all=$r+$g+$b
	Return $all

EndFunc

Func _GetPixelFromBMP($sImage,$x,$y );git die farbe an $x,$y einer 24bpp-bitmap aus
    Local $pbitmap, $Bmp
    Local $tBD, $iWidth, $iHeight, $iStride
    Local $pData, $color

    _GDIPlus_Startup()
    $pbitmap = _GDIPlus_ImageLoadFromFile($sImage)       ;bild laden
    If @error Then Return SetError(1, 2, 0)
    $tBD = _GDIPlus_BitmapLockBits($pbitmap, 0, 0, _GDIPlus_ImageGetWidth($pBitmap), _GDIPlus_ImageGetHeight($pBitmap), $GDIP_ILMREAD, $GDIP_PXF24RGB)
    If @error Then MsgBox(0, "", "Error locking region " & @error)
    $iWidth = DllStructGetData($tBD, 1)      ;breite der bitmap
    $iHeight = DllStructGetData($tBD, 2)    ;hoehe der bitmap
    $iStride = DllStructGetData($tBD, 3)    ;Erkl?rung : http://www.autoitscript.com/forum/index....?showtopic=106673&view=findpos
    $pData = DllStructGetData($tBD, 5)      ;pointer auf die bitmapdaten
    $Bmp = DllStructCreate("byte[" & $iStride * $iHeight & "]", $pData)  ;struct gefüllt mit den Daten der Bitmap
    $position    = $istride * ($y - 1) + 3 * ($x-1)      ;Umrechnung der Position von x- und y- Koordinaten
	$substruct = DllStructCreate("ubyte[3]", DllStructGetPtr($Bmp) + $position)  ;eine 3-byte struct an die position vom pixel schreiben
	$tmp = DllStructgetData($substruct, 1)  ;Daten aus der struct lesen
	$color=get_val($tmp)

    _GDIPlus_BitmapUnlockBits($pbitmap, $tBD)
    _GDIPlus_ImageDispose($pbitmap)
    _GDIPlus_Shutdown()
    return $color
EndFunc   ;==>_GetImagePixels

Func Close()
	Exit
EndFunc

