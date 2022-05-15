(function ($) {
	$.fn.extend({
		insertAtCaret: function (myValue) {
			var $t = $(this)[0];
			if (document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			} else if ($t.selectionStart || $t.selectionStart == '0') {
				var startPos = $t.selectionStart;
				var endPos = $t.selectionEnd;
				var scrollTop = $t.scrollTop;
				$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
				this.focus();
				$t.selectionStart = startPos + myValue.length;
				$t.selectionEnd = startPos + myValue.length;
				$t.scrollTop = scrollTop;
			} else {
				this.value += myValue;
				this.focus();
			}
		}
	});
})(jQuery);
 $(function() {
// 	$("#wmd-button-bar .wmd-edittab").remove()
//  $("#wmd-button-row .wmd-spacer").remove()
//  $("#wmd-button-row #wmd-more-button").remove()
    $("#wmd-button-row #wmd-code-button").remove()
    $("#wmd-button-row #wmd-heading-button").remove()
    $("#wmd-fullscreen-button").on("click",function() {
	$(".fullscreen #text").css("top",$('.fullscreen #wmd-button-bar').outerHeight())
}
)
$("#wmd-button-row #wmd-fullscreen-button").before(`
<li class="wmd-button custom" id="b-wmd-title" title="插入标题">
  <svg t="1632494349172" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2457" width="64" height="64"><path d="M256 213.333333h104.874667v267.093334h324.48V213.333333h104.874666v640h-104.874666v-283.264H360.874667V853.333333H256z" p-id="2458" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-linecode" title="行内代码">
  <svg t="1630835908894" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5268" width="60" height="60"><path d="M539.584 217.92c8.192-20.48 28.8-28.8 49.28-20.48 20.544 8.192 32.896 28.736 28.8 49.28v4.096l-164.352 493.12v4.096c-8.256 20.48-28.8 28.8-49.28 20.48-20.608-8.192-32.896-28.736-28.8-49.28v-4.096L539.52 222.08v-4.16z m163.52-12.288c16.384-16.448 36.928-16.448 53.376-4.16l4.096 4.16 246.592 246.528 4.096 4.096c12.352 16.448 12.352 36.992 0 49.28l-4.096 4.16-246.592 246.528-4.096 4.096c-16.448 12.352-36.992 12.352-49.28 0l-4.096-4.096-4.16-4.096c-12.288-16.448-12.288-36.992 0-49.28l4.16-4.16 217.728-217.792-217.728-217.728-4.16-4.16c-12.288-16.384-12.288-36.928 4.16-53.376zM260.16 205.632a39.68 39.68 0 0 1 57.536 0c16.448 16.448 16.448 36.992 4.096 53.376l-4.096 4.16L99.84 480.896l217.792 217.792c16.448 16.448 16.448 36.992 4.096 53.44l-4.096 4.096c-16.448 16.448-36.992 16.448-53.44 4.096l-4.096-4.096L13.632 509.696c-16.448-16.448-16.448-36.992-4.16-53.44l4.16-4.096 246.528-246.528z" fill="#909399" p-id="5269"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-code" title="代码块">
  <svg t="1630837049158" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11573" width="64" height="64"><path d="M512.332252 44.521739h227.26022c112.301103 0 142.203764 66.450357 142.203764 138.881246v649.219987c0 64.456846-63.127839 146.190785-148.848799 146.190785H293.046074c-83.062946 0-150.84231-65.785853-150.84231-144.861778V329.59377c0-39.205711 22.593121-71.766385 51.166775-111.636599l84.391953-106.320571c35.883193-43.857236 43.192732-67.11486 107.649578-67.114861h126.920182zM134.894225 214.634653C104.32706 255.833874 97.017521 291.717067 97.017521 331.587281v502.364698C97.017521 938.27904 174.099935 1023.335496 293.046074 1023.335496H734.940947c99.011032 0 192.041531-89.707982 192.041532-186.060999V183.402985c0-120.275146-66.450357-182.738482-190.712524-182.738481H386.076574C327.60026 0.664504 297.697599 21.264114 257.827385 59.805321L134.894225 214.634653z m453.191434 524.957819c3.987021 19.270604 5.316029 37.876703-13.954575 41.863725-19.270604 3.987021-25.251136-7.974043-29.238157-27.244646l-63.792343-293.046074c-3.987021-19.270604-5.316029-37.876703 14.619079-41.863725 19.270604-3.987021 25.251136 7.974043 29.238157 27.244646l63.127839 293.046074z m148.184296-170.112913c19.935107 17.277093 20.599611 43.192732 1.99351 61.798832-27.90915 28.573653-69.772875 66.450357-69.772874 66.450357-15.283582 13.290071-18.6061 24.586632-12.625568 31.896171 7.309539 7.309539 20.599611 5.980532 31.231667-3.987022l75.088904-70.437378c27.90915-25.915639 33.225178-75.753407 2.658014-104.32706l-93.0305-87.714471c-11.961064-7.974043-20.599611-11.961064-28.573653-4.651525-9.967554 9.30305-5.316029 20.599611 5.980532 31.231667l87.049968 79.740429z m-324.277742-79.740429c11.296561-10.632057 15.948086-21.928618 5.980532-31.231667-7.974043-7.309539-16.612589-3.322518-28.573654 4.651525L297.033095 550.873459c-30.567164 28.573653-24.586632 78.411421 2.658015 104.32706l75.088903 70.437378c10.632057 9.967554 23.922128 11.961064 31.231668 3.987022 6.645036-6.645036 2.658014-18.6061-12.625568-31.896171 0 0-41.863725-37.876703-69.772875-66.450357-18.6061-18.6061-17.941596-44.521739 1.993511-61.798832l86.385464-79.740429z" fill="#707070" p-id="11574"></path><path d="M123.597664 293.710578h159.480856c28.573653 0 43.192732-10.632057 43.192732-37.876704V22.593121h44.52174v231.247242c0 49.173264-24.586632 83.062946-89.043479 83.062947H122.93316l0.664504-43.192732z" fill="#707070" p-id="11575"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-reply" title="回复可见">
  <svg t="1630837221852" class="icon" viewBox="0 0 1363 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="16215" width="64" height="64"><path d="M980.847063 113.45883l-59.891861 60.718963c-83.196655-52.010073-164.836414-77.601558-244.82197-77.601559-181.135174 0-370.784625 131.314487-567.245498 403.090227 87.964651 101.295577 174.761629 179.043094 260.390934 233.875041l-66.897895 67.822303c-100.71174-68.989975-201.423481-165.809474-302.135221-290.507151C225.557663 178.361952 450.821121 12.114601 676.133232 12.114601c101.587495 0 203.126336 33.765192 304.713831 101.344229z m141.580272 116.086093c76.482539 74.585072 153.013731 168.339431 229.544924 281.311731-225.263458 278.976386-450.526916 418.464579-675.839027 418.464579-68.600751 0-137.152848-12.893049-205.704946-38.776452l74.974295-75.996009a486.530147 486.530147 0 0 0 130.730651 18.24488c183.470518 0 372.536133-108.885447 567.245498-333.127191-60.378391-83.537226-120.172946-153.840832-179.237706-211.105431l58.237658-59.016107z m-462.300945 468.674491l243.119114-246.476173a217.235711 217.235711 0 0 1-243.119114 246.476173z m-156.516748-100.8577a217.235711 217.235711 0 0 1 296.63743-300.724283l-296.63743 300.67563zM1156.727711 0l64.027367 64.903122L308.657012 989.699625 244.678298 924.74785 1156.727711 0z" p-id="16216" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-delete" title="删除线">
  <svg t="1607494660243" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1750" width="15" height="15">
    <path d="M968 542.9V481c0-1.7-0.5-3-2.3-3H571.6l-0.5-0.1c-10.7-2.1-21.6-4.2-32.5-6.2-16.9-3.1-23.2-4.3-31.8-6-53.1-10.4-85.4-20.7-111.6-35.8-37.9-22.1-56.3-52.2-56.3-92 0-39.7 16.4-72.8 47.3-95.7 30.1-22.3 72.8-34 123.3-34 57.8 0 102.6 15.3 133.1 45.5 15.6 15.4 27.1 34.3 34 56.2 1.6 4.9 3.1 11.4 4.6 18.8 0.5 2.5 2.7 4.3 5.3 4.3h75c2.9 0 5.4-2.3 5.4-5.2v-0.8c-1-6.8-1.3-12.1-2-15.9-7.3-43.8-28-82-59.9-110.8-44.7-40.8-110.8-62.4-191-62.4-73.4 0-139.4 18.3-185.9 51.5-25.8 18.6-45.6 41.4-58.8 67.9-13.4 27.2-20.3 58.7-20.3 93.5 0 29.5 5.6 54.5 17.2 76.5 8.2 15.5 19.3 29.2 34 41.9l10.2 8.8H59.2c-1.8 0-4.2 1.4-4.2 3.1V543c0 1.8 2.4 3 4.2 3h446.7l0.5 0.2c1.3 0.3 2.6 0.6 3.8 0.8 0.8 0.2 1.5 0.3 2.3 0.5 33 6.6 51.7 10.9 69 15.8 24.3 6.9 42.8 14.1 58 22.6 38.7 21.8 57.5 53.2 57.5 96 0 37.9-16.6 71.8-46.8 95.4-32.2 25.2-79.7 38.6-137.5 38.6-45.6 0-84.6-8.9-116-26.4-30.9-17.3-52.4-42.3-63.8-74.3-0.9-2.4-1.8-5.8-2.9-9.9-0.6-2.3-2.8-4.3-5.2-4.3h-82.1c-3 0-5.7 3-5.7 6v0.8c0 2.2 0.5 4.1 0.7 5.4 6.5 48.9 30.4 89 70.9 119 47.6 35.2 115 53.8 194.6 53.8 85.6 0 157.4-20.1 207.3-58 25-18.9 44.3-42.2 57.3-69.3 13.1-27.4 19.8-58.4 19.8-92.1 0-32-5.8-58.6-17.8-81.5-5.7-11.1-13-21.4-21.7-30.7l-7.9-8.5h225.3c2 0.1 2.5-1.3 2.5-3z" p-id="1751" fill="#888888"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-table" title="插入表格">
  <svg t="1607495516074" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2817" width="15" height="15">
    <path d="M960 591.424V368.96c0-0.288 0.16-0.512 0.16-0.768s-0.16-0.512-0.16-0.768V192a32 32 0 0 0-32-32H96a32 32 0 0 0-32 32v175.424c0 0.288-0.16 0.512-0.16 0.768s0.16 0.48 0.16 0.768v222.464c0 0.288-0.16 0.512-0.16 0.768s0.16 0.48 0.16 0.768V864a32 32 0 0 0 32 32h832a32 32 0 0 0 32-32V592.96c0-0.288 0.16-0.512 0.16-0.768s-0.16-0.512-0.16-0.768z m-560-31.232v-160h208v160H400z m208 64V832H400V624.192h208z m-480-224h208v160H128v-160z m544 0h224v160H672v-160zM896 224v112.192H128V224h768zM128 624.192h208V832H128V624.192zM672 832V624.192h224V832H672z" p-id="2818" fill="#888888"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-nhtml" title="原生代码">
<svg t="1630837111882" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13888" width="64" height="64"><path d="M913.408 890.368L512 1024l-400.896-133.632L22.016 0h979.456l-88.064 890.368zM111.104 89.088l66.56 734.72 333.824 111.104 333.824-111.104 66.56-734.72H111.104z m200.704 200.192l22.016 133.632h445.44l-22.016 311.808-250.88 89.088L284.672 721.92l-14.336-120.32h71.168l15.872 66.56 152.576 66.56 169.984-66.56 11.264-155.648H260.608l-35.84-311.808h572.928l-7.168 89.088-478.72-0.512z" p-id="13889" fill="#707070"></path></svg>
</li>
<li class="wmd-spacer wmd-spacer4" id="wmd-spacer4"></li>
<li class="wmd-button custom" id="b-wmd-tabs" title="Tab栏切换">
  <svg t="1630837574969" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6453" width="64" height="64"><path d="M922.399 73.632 421.153 73.632C408.789 31.46 369.757 0.571 323.639 0.571l-76.486 0L101.601 0.571C45.578 0.571 0 46.148 0 102.172l0 819.656c0 56.023 45.578 101.602 101.601 101.602l820.798 0c56.023 0 101.601-45.578 101.601-101.602L1024 175.233C1024 119.209 978.423 73.632 922.399 73.632zM966.921 175.233l0 46.805L717.485 222.038l0-91.327 204.914 0C946.949 130.711 966.921 150.684 966.921 175.233zM425.24 222.038l0-91.327 235.167 0 0 91.327L425.24 222.038zM101.601 57.65l145.552 0 76.486 0c24.549 0 44.522 19.973 44.522 44.522l0 119.866L57.079 222.038 57.079 102.172C57.079 77.622 77.051 57.65 101.601 57.65zM922.399 966.35 101.601 966.35c-24.55 0-44.522-19.973-44.522-44.521L57.079 279.117l909.842 0 0 642.711C966.921 946.377 946.949 966.35 922.399 966.35z" p-id="6454" fill="#707070"></path><path d="M843.059 329.917 183.224 329.917c-40.351 0-73.062 32.709-73.062 73.062l0 440.65c0 40.352 32.711 73.062 73.062 73.062l659.834 0c40.352 0 73.062-32.711 73.062-73.062l0-440.65C916.12 362.627 883.41 329.917 843.059 329.917zM879.59 843.629c0 20.145-16.388 36.531-36.531 36.531L183.224 880.16c-20.143 0-36.531-16.387-36.531-36.531l0-440.65c0-20.144 16.388-36.531 36.531-36.531l659.834 0c20.144 0 36.531 16.387 36.531 36.531L879.589 843.629z" p-id="6455" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-btn" title="按钮">
  <svg t="1630837495791" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4651" width="64" height="64"><path d="M856.73 796.7h-690c-57.9 0-105-47.1-105-105v-360c0-57.9 47.1-105 105-105h690c57.9 0 105 47.1 105 105v360c0 57.89-47.1 105-105 105z m-690-500.01c-19.3 0-35 15.7-35 35v360c0 19.3 15.7 35 35 35h690c19.3 0 35-15.7 35-35v-360c0-19.3-15.7-35-35-35h-690z" p-id="4652" fill="#707070"></path><path d="M233.16 431.69H790.3v160H233.16z" p-id="4653" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-note" title="note">
  <svg t="1629634612645" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4343" width="60" height="60">
    <path d="M987.2 511.648L512.416 36.864A84.48 84.48 0 0 0 447.168 12.16h0.224L111.52 31.904A84.928 84.928 0 0 0 31.872 111.328v0.224L12.128 447.424a84.352 84.352 0 0 0 24.672 64.992l474.816 474.816c15.36 15.328 36.544 24.8 59.968 24.8s44.608-9.472 59.968-24.8l355.616-355.648c15.328-15.36 24.8-36.544 24.8-59.968s-9.472-44.608-24.8-59.968z m-39.296 80.64L592.288 947.904a29.216 29.216 0 0 1-41.344 0L76.128 473.088a29.12 29.12 0 0 1-8.544-20.64l0.064-1.824v0.096l19.744-335.872a29.344 29.344 0 0 1 27.392-27.488h0.096l335.904-19.744 1.664-0.032c8.096 0 15.424 3.264 20.704 8.576l474.816 474.816a29.248 29.248 0 0 1 0 41.376zM236.864 236.864a130.816 130.816 0 0 0 0 184.736 130.624 130.624 0 0 0 184.704-184.736 130.88 130.88 0 0 0-184.704 0z m145.44 145.44c-28.384 28.384-77.792 28.384-106.144 0a75.072 75.072 0 1 1 106.112 0z" p-id="4344" fill="#707070"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-note-ico" title="带有标签note">
  <svg t="1629634926182" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5260" width="60" height="60">
    <path d="M711.238 521.181c0-12.464-10.103-22.567-22.567-22.567l-333.26 0c-12.464 0-22.567 10.103-22.567 22.567s10.103 22.567 22.567 22.567l333.26 0C701.135 543.748 711.238 533.645 711.238 521.181z" p-id="5261" fill="#707070"></path>
    <path d="M821.055 180.193c10.696 0 13.726 3.029 13.726 13.726l0 182.565 0.103 0c0.546 12.049 10.409 21.672 22.593 21.672 12.185 0 22.046-9.623 22.594-21.672l0.103 0L880.174 157.625c0-12.464-10.103-22.567-22.567-22.567L420.685 135.058c10.929 13.889 20.35 29.018 28.033 45.135L821.055 180.193z" p-id="5262" fill="#707070"></path>
    <path d="M355.411 657.226c-12.464 0-22.567 10.103-22.567 22.567s10.103 22.567 22.567 22.567l222.123 0c12.464 0 22.567-10.103 22.567-22.567s-10.103-22.567-22.567-22.567L355.411 657.226z" p-id="5263" fill="#707070"></path>
    <path d="M857.477 703.121l0 0.001c-12.534 0-22.695 10.161-22.695 22.696 0 0.109 0.031 0.21 0.032 0.319l-0.032 0 0 150.657c0 10.697-3.03 13.726-13.726 13.726L265.094 890.52c-10.696 0-13.726-3.03-13.726-13.726L251.368 522.563c-6.401 0.512-12.869 0.783-19.401 0.783-8.783 0-17.451-0.485-25.99-1.405l0 391.146c0 12.464 10.103 22.567 22.567 22.567l629.063 0c12.464 0 22.567-10.104 22.567-22.567L880.174 726.136l-0.032 0c0.001-0.108 0.032-0.21 0.032-0.319C880.174 713.283 870.013 703.121 857.477 703.121z" p-id="5264" fill="#707070"></path>
    <path d="M964.467 407.538c-6.647 0-12.562 2.929-16.694 7.502L736.421 626.392l31.917 31.915 212.304-212.509c3.943-4.064 6.392-9.584 6.392-15.692l0-0.001C987.034 417.641 976.93 407.538 964.467 407.538z" p-id="5265" fill="#707070"></path>
    <path d="M753.307 362.569c0-12.464-10.103-22.567-22.567-22.567L465.221 340.002c-3.801 15.705-9.146 30.805-15.867 45.134L730.74 385.136C743.204 385.136 753.307 375.033 753.307 362.569z" p-id="5266" fill="#707070"></path>
    <path d="M728.105 634.943 699.871 693.271 760.02 666.858Z" p-id="5267" fill="#707070"></path>
    <path d="M426.966 283.346c0-107.696-87.304-195-195-195s-195 87.304-195 195 87.304 195 195 195S426.966 391.041 426.966 283.346zM81.966 283.346c0-82.843 67.157-150 150-150s150 67.157 150 150-67.157 150-150 150S81.966 366.189 81.966 283.346z" p-id="5268" fill="#707070"></path>
    <path d="M315.078 261.614l-61.381 0 0-61.381c0-9.327-9.729-16.888-21.731-16.888-12.003 0-21.732 7.56-21.732 16.888l0 61.381-61.38 0 0 0c-9.327 0-16.888 9.729-16.888 21.732s7.56 21.732 16.888 21.732l61.381 0 0 61.381c0 9.327 9.729 16.888 21.732 16.888 12.002 0 21.731-7.561 21.731-16.888l0-61.381 61.381 0c9.327 0 16.888-9.729 16.888-21.732S324.406 261.614 315.078 261.614z" p-id="5269" fill="#707070"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-hide-toggle" title="hide-toggle">
  <svg t="1629635215041" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="8154" width="60" height="60">
    <path d="M131.11 192.37m32.17 0l700.08 0q32.17 0 32.17 32.17l0 0q0 32.17-32.17 32.17l-700.08 0q-32.17 0-32.17-32.17l0 0q0-32.17 32.17-32.17Z" fill="#999999" p-id="8155"></path>
    <path d="M131.11 766.83m32.17 0l700.08 0q32.17 0 32.17 32.17l0 0q0 32.17-32.17 32.17l-700.08 0q-32.17 0-32.17-32.17l0 0q0-32.17 32.17-32.17Z" fill="#999999" p-id="8156"></path>
    <path d="M449.33 385.09m32.17 0l381.87 0q32.17 0 32.17 32.17l0 0q0 32.17-32.17 32.17l-381.87 0q-32.17 0-32.17-32.17l0 0q0-32.17 32.17-32.17Z" fill="#999999" p-id="8157"></path>
    <path d="M449.33 578.11m32.17 0l381.87 0q32.17 0 32.17 32.17l0 0q0 32.17-32.17 32.17l-381.87 0q-32.17 0-32.17-32.17l0 0q0-32.17 32.17-32.17Z" fill="#999999" p-id="8158"></path>
    <path d="M321.3 512.16L130.08 384.62V639.7L321.3 512.16z" fill="#999999" p-id="8159"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-hide-inline" title="行内隐藏">
  <svg t="1630857885531" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2978" width="64" height="64"><path d="M128 490.666667c0-11.776 9.045333-21.333333 20.224-21.333334h727.552c11.178667 0 20.224 9.557333 20.224 21.333334v42.666666c0 11.776-9.045333 21.333333-20.224 21.333334H148.224A20.778667 20.778667 0 0 1 128 533.333333v-42.666666zM128 789.333333c0-11.776 9.045333-21.333333 20.224-21.333333h97.706667c11.093333 0 20.181333 9.557333 20.181333 21.333333v42.666667c0 11.776-9.045333 21.333333-20.224 21.333333h-97.706667A20.778667 20.778667 0 0 1 128 832v-42.666667z m286.293333 0c0-11.776 9.088-21.333333 20.224-21.333333h154.965334c11.136 0 20.181333 9.557333 20.181333 21.333333v42.666667c0 11.776-9.045333 21.333333-20.181333 21.333333h-154.965334a20.778667 20.778667 0 0 1-20.181333-21.333333v-42.666667z m343.594667 0c0-11.776 9.045333-21.333333 20.224-21.333333h97.706667c11.093333 0 20.181333 9.557333 20.181333 21.333333v42.666667c0 11.776-9.045333 21.333333-20.224 21.333333h-97.706667a20.778667 20.778667 0 0 1-20.181333-21.333333v-42.666667zM128 192c0-11.776 9.045333-21.333333 20.224-21.333333h97.706667c11.093333 0 20.181333 9.557333 20.181333 21.333333v42.666667c0 11.776-9.045333 21.333333-20.224 21.333333h-97.706667A20.778667 20.778667 0 0 1 128 234.666667v-42.666667z m286.293333 0c0-11.776 9.088-21.333333 20.224-21.333333h154.965334c11.136 0 20.181333 9.557333 20.181333 21.333333v42.666667c0 11.776-9.045333 21.333333-20.181333 21.333333h-154.965334a20.778667 20.778667 0 0 1-20.181333-21.333333v-42.666667z m343.594667 0c0-11.776 9.045333-21.333333 20.224-21.333333h97.706667c11.093333 0 20.181333 9.557333 20.181333 21.333333v42.666667c0 11.776-9.045333 21.333333-20.224 21.333333h-97.706667a20.778667 20.778667 0 0 1-20.181333-21.333333v-42.666667z" p-id="2979" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-hide-block" title="块内隐藏">
  <svg t="1629635540353" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10617" width="60" height="60">
    <path d="M884.48 296.106667l-5.973333-10.666667a90.88 90.88 0 0 0-31.146667-29.44l-201.386667-116.48a85.333333 85.333333 0 0 0-42.666666-11.52h-11.946667a81.92 81.92 0 0 0-42.666667 11.52L176.64 354.986667a85.333333 85.333333 0 0 0-31.146667 31.146666l-5.973333 10.666667a85.333333 85.333333 0 0 0-11.52 42.666667v245.76a85.333333 85.333333 0 0 0 11.52 42.666666l5.973333 10.666667a90.88 90.88 0 0 0 31.146667 31.146667l201.386667 114.773333a85.333333 85.333333 0 0 0 42.666666 11.52h11.946667a81.92 81.92 0 0 0 42.666667-11.52l372.053333-215.466667a85.333333 85.333333 0 0 0 31.146667-31.146666l5.973333-10.666667a85.333333 85.333333 0 0 0 11.52-42.666667V338.773333a85.333333 85.333333 0 0 0-11.52-42.666666zM591.36 213.333333h11.946667L768 307.2l-341.333333 197.546667-170.666667-97.28z m213.333333 384L469.333333 789.333333v-210.346666l341.333334-197.973334v203.52z" p-id="10618" fill="#707070"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-wcheakbox" title="复选框(主题需要开启魔改美化)">
  <svg t="1630858009517" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4111" width="64" height="64"><path d="M874.6 96.3H150.1c-29.6 0-53.7 24.2-53.7 53.7v724.5c0 29.6 24.2 53.7 53.7 53.7h724.5c29.6 0 53.7-24.2 53.7-53.7V150.1c0-29.6-24.1-53.8-53.7-53.8z m-91.6 298C683.9 490.7 584.8 587.1 484.3 682c-31.7 30.3-48.2 30.3-78.5 1.4-53.7-52.3-107.4-104.6-162.4-155.6-20.6-19.3-30.3-39.9-9.6-63.3 17.9-20.6 42.7-16.5 68.8 8.3 46.8 45.4 93.6 89.5 140.4 136.3 12.4-11 20.6-19.3 30.3-27.5l251.9-243.7c20.6-19.3 41.3-28.9 66.1-9.6 20.6 16.5 17.9 41.2-8.3 66z" p-id="4112" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-radio" title="单选框(主题需要开启魔改美化)">
  <svg t="1630646105700" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2157" width="60" height="60">
    <path d="M512 304c-114.4 0-208 93.6-208 208s93.6 208 208 208 208-93.6 208-208-93.6-208-208-208z m0-208C283.202 96 96 283.202 96 512s187.202 416 416 416 416-187.202 416-416S740.798 96 512 96z m0 748.8c-183.036 0-332.8-149.766-332.8-332.8S328.964 179.2 512 179.2 844.8 328.964 844.8 512 695.036 844.8 512 844.8z" p-id="2158" fill="#707070"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-inline-tag" title="行内标签(主题需要开启魔改美化)">
  <svg t="1630401180483" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2606" width="60" height="60">
    <path d="M159.288889 247.751111a42.552889 42.552889 0 0 1 0-85.105778h723.342222a42.552889 42.552889 0 1 1 0 85.105778H159.288889z m253.610667 452.266667a100.010667 100.010667 0 0 1-100.010667-99.555556V451.527111c0.227556-55.011556 44.942222-99.555556 100.010667-99.555555h218.680888c55.068444 0 99.783111 44.544 100.010667 99.555555v148.935111a100.010667 100.010667 0 0 1-99.157333 99.555556z m0.853333-82.944l-0.853333-2.161778h218.680888a14.904889 14.904889 0 0 0 14.904889-14.449778v-146.773333a14.904889 14.904889 0 0 0-14.051555-14.449778H413.752889a14.904889 14.904889 0 0 0-14.904889 14.449778v148.935111c0.227556 8.021333 6.826667 14.449778 14.904889 14.449778zM159.288889 589.084444a42.552889 42.552889 0 0 1 0-85.105777h97.564444a42.552889 42.552889 0 0 1 0 85.105777H159.288889z m625.777778 0a42.552889 42.552889 0 0 1 0-85.105777h97.564444a42.552889 42.552889 0 1 1 0 85.105777H785.066667z m-625.777778 323.697778a42.552889 42.552889 0 0 1 0-85.105778h723.342222a42.552889 42.552889 0 1 1 0 85.105778H159.288889z" p-id="2607" fill="#707070"></path>
  </svg>
</li>
<li class="wmd-button custom" id="b-wmd-md-link" title="现代md插入链接">
  <svg t="1630831290611" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2190" width="64" height="64"><path d="M414.1 831.8c-61.3 61.3-160.7 61.3-222 0-61.3-61.3-61.3-160.7 0-222l140.7-140.7c15.63-15.63 15.63-40.97 0-56.6-15.63-15.63-40.97-15.63-56.6 0L135.5 553.2c-92.6 92.6-92.6 242.6 0 335.2s242.6 92.6 335.2 0l140.7-140.7c15.63-15.63 15.63-40.97 0-56.6-15.63-15.63-40.97-15.63-56.6 0L414.1 831.8zM887.8 136c-92.6-92.6-242.6-92.6-335.2 0L411.9 276.7c-15.63 15.63-15.63 40.97 0 56.6 15.63 15.63 40.97 15.63 56.6 0l140.7-140.7c61.3-61.3 160.7-61.3 222 0 61.3 61.3 61.3 160.7 0 222L690.5 555.3c-15.63 15.63-15.63 40.97 0 56.6 15.63 15.63 40.97 15.63 56.6 0l140.7-140.7c92.6-92.6 92.6-242.7 0-335.2z" p-id="2191" fill="#707070"></path><path d="M320 703.8c17.2 17.2 45 17.2 62.2 0l321-321c17.2-17.2 17.2-45 0-62.2-17.2-17.2-45-17.2-62.2 0l-321 321c-17.2 17.1-17.2 45 0 62.2z" p-id="2192" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-md-img" title="现代md插入图片">
  <svg t="1630832731295" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3768" width="60" height="60"><path d="M952.439467 325.973333a478.190933 478.190933 0 0 0-880.64 0A477.610667 477.610667 0 0 0 512 989.866667a478.3616 478.3616 0 0 0 440.439467-663.893334z m-142.062934 484.4032A422.024533 422.024533 0 0 1 176.622933 768l93.969067-80.8448L482.6624 885.76c0.443733 0.477867 0.887467 0.9728 1.3824 1.4336a27.0848 27.0848 0 0 0 37.444267-39.133867l-68.8128-64.529066L701.44 549.290667l176.401067 173.1584a422.0416 422.0416 0 0 1-67.464534 87.927466z m92.0576-138.0864L720.9472 492.4928l-18.551467-18.3808-19.012266 17.902933L413.115733 746.496l-123.272533-115.438933L272.110933 614.4l-18.397866 15.837867-107.963734 91.323733a424.5504 424.5504 0 0 1-22.4768-45.329067 422.024533 422.024533 0 0 1 777.540267-328.362666 423.253333 423.253333 0 0 1 1.621333 324.420266z m-525.653333-415.744A135.2192 135.2192 0 1 0 512 391.748267a135.202133 135.202133 0 0 0-135.2192-135.202134z m0 216.337067a81.117867 81.117867 0 1 1 81.134933-81.134933 81.117867 81.117867 0 0 1-81.134933 81.134933z" fill="#707070" p-id="3769"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-mark" title="文字高亮">
  <svg t="1630842990651" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2341" width="64" height="64"><path d="M1024 510.293333l-66.645333-69.333333-196.693334 167.893333-284.16-297.984 162.261334-200.576L571.562667 42.666667 181.418667 524.586667l59.008 61.909333L0 829.482667 355.157333 981.333333l131.157334-139.264 66.133333 69.333334L1024 510.293333zM413.098667 389.973333l271.018666 284.202667-111.445333 95.146667-251.904-265.344 92.330667-114.048z" p-id="2342" fill="#707070"></path></svg>
</li>
<li class="wmd-spacer wmd-spacer4" id="wmd-spacer4"></li>
<li class="wmd-button custom" id="b-wmd-md-draft" title="保存草稿">
  <svg t="1630841483224" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10796" width="64" height="64"><path d="M363.175 705.746l241.359-241.358c16.594-16.594 16.594-43.747 0-60.34s-43.747-16.594-60.34 0L302.835 645.407c-16.592 16.594-16.592 43.747 0 60.339 16.594 16.593 43.747 16.593 60.34 0z" p-id="10797" fill="#707070"></path><path d="M981.333 922.848H387.434l442.898-442.899 46.99-46.988 90.964-90.965c33.188-33.186 33.188-87.491 0-120.679L787.266 40.298c-33.186-33.188-87.493-33.188-120.679 0l-90.965 90.964-60.34 60.34L43.075 663.808l-30.168 30.17 0.036 0.036C5.21 701.747 0.408 712.415 0.408 724.148v237.354C0.28 962.843 0 964.141 0 965.515c0 11.837 4.903 22.569 12.757 30.319 7.75 7.854 18.481 12.759 30.318 12.759 1.385 0 2.694-0.281 4.047-0.411h934.212c23.467 0 42.667-19.2 42.667-42.667-0.001-23.466-19.201-42.667-42.668-42.667zM85.741 827.154l95.694 95.694H85.741v-95.694z m611.016-696.348c16.592-16.592 43.747-16.592 60.339 0l120.681 120.681c16.594 16.594 16.592 43.747 0 60.339l-60.795 60.795-181.02-181.019 60.795-60.796zM133.585 693.978l442.036-442.036L756.64 432.961 314.604 874.997l-30.17 30.17-30.17-30.17-120.679-120.678-30.17-30.17 30.17-30.171z" p-id="10798" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-md-submit" title="发布文章">
  <svg t="1630837719917" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="8930" width="64" height="64"><path d="M376.74 601.66L161.26 470.4c-19.83-12.08-17.26-41.65 4.37-50.11l700.82-274.36a26.91 26.91 0 0 1 36.22 30.19L780.23 805.94a29 29 0 0 1-44.11 18.89l-304.49-195 304.31-328.51zM434 705.22v145a9.34 9.34 0 0 0 16 6.56l88.62-90a9.35 9.35 0 0 0-1.73-14.5l-88.61-55.07a9.35 9.35 0 0 0-14.28 8.01z" p-id="8931" fill="#707070"></path></svg>
</li>
<li class="wmd-button custom" id="b-wmd-md-explain" title="说明">
  <svg t="1630859308937" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6373" width="64" height="64"><path d="M512 117.76c217.6 0 394.24 176.64 394.24 394.24s-176.64 394.24-394.24 394.24-394.24-176.64-394.24-394.24 176.64-394.24 394.24-394.24m0-51.2c-245.76 0-445.44 199.68-445.44 445.44s199.68 445.44 445.44 445.44 445.44-199.68 445.44-445.44-199.68-445.44-445.44-445.44z" p-id="6374"></path><path d="M555.52 345.6c0-24.576-19.968-44.544-44.544-44.544-24.576 0-44.544 19.968-44.544 44.544s19.968 44.544 44.544 44.544c24.576 0.512 44.544-19.968 44.544-44.544z m-95.232 369.664c8.192 7.168 18.432 8.192 26.112 7.168 4.608-0.512 9.728-2.048 11.776-2.56l75.264-25.6c16.384-5.632 24.064-15.872 18.432-34.816-5.632-16.384-19.456-20.48-32.256-17.408l-41.472 11.776 38.912-183.296c1.536-8.192 0.512-20.992-8.192-30.208-7.68-8.192-20.48-9.728-28.672-7.68l-71.68 24.064c-12.8 3.072-19.968 19.968-16.384 33.28 4.608 17.408 18.432 23.552 31.232 19.968l31.232-9.728-39.936 190.976c-2.56 8.704-0.512 18.432 5.632 24.064z" p-id="6375"></path></svg>
</li>
<li class="wmd-spacer wmd-spacer4" id="wmd-spacer4"></li>
`);
$("#b-wmd-md-link").on("click",function() {
    $("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 45%;" role="dialog">
    <div><p><b>插入链接(markdown通用格式)</b></p><p>请在下方的输入框内输入要插入的链接地址和标题</p></div>
  <form>
    链接描述：<input type="text" name="link-title">
    链接地址：<input type="text" name="link-link" value="https://" onfocus="this.select();">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>`);
$(".btn-ok").click(function(){
    let title = $('input[name="link-title"]').val();
    let link = $('input[name="link-link"]').val();
    insertAtCursor('['+title+']('+link+' "'+ title+'")');
});
});


$("#b-wmd-md-img").on("click",function() {
    $("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 45%;" role="dialog">
    <div><p><b>插入图片(markdown通用格式)</b></p><p>请在下方的输入框内输入要插入的图片链接地址和标题</p></div>
  <form>
    图片描述：<input type="text" name="img-title">
    图片地址：<input type="text" name="img-link" value="https://" onfocus="this.select();">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let title = $('input[name="img-title"]').val();
    let link = $('input[name="img-link"]').val();
    insertAtCursor('!['+title+']('+link+' "'+ title+'")');
});
});
// 行内代码
$("#b-wmd-linecode").on("click",function() {
    $("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" role="dialog">
    <div><p><b>插入行内代码</b></p></div>
 <form>
    填写内行代码：<input type="text" name="insert-linecode" value="内行代码" onfocus="this.select();">
    <input type="text" style="display:none;">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let linecode = $('input[name="insert-linecode"]').val();
    insertAtCursor(' `'+linecode+'` ');
});
});
$("#b-wmd-code").insertAtCaret($(this).attr("data-param"));
// 代码块
$("#b-wmd-code").on("click",function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 38%;" role="dialog">
    <div><p><b>插入代码块(语言版)</b></p></div>
  <form>
<p>代码语言：<select id="select-code"><option selected="selected" value="other">请选择代码语言</option><option value="asp" mode="vbscript">ASP</option><option value="actionscript" mode="clike">ActionScript(3.0)/Flash/Flex</option><option value="bash" mode="shell">Bash/Bat</option><option value="css" mode="css">CSS</option><option value="c" mode="clike">C</option><option value="cpp" mode="clike">C++</option><option value="csharp" mode="clike">C#</option><option value="coffeescript" mode="coffeescript">CoffeeScript</option><option value="d" mode="d">D</option><option value="dart" mode="dart">Dart</option><option value="delphi" mode="pascal">Delphi/Pascal</option><option value="erlang" mode="erlang">Erlang</option><option value="go" mode="go">Golang</option><option value="groovy" mode="groovy">Groovy</option><option value="html" mode="text/html">HTML</option><option value="java" mode="clike">Java</option><option value="json" mode="text/json">JSON</option><option value="javascript" mode="javascript">Javascript</option><option value="lua" mode="lua">Lua</option><option value="less" mode="css">LESS</option><option value="markdown" mode="gfm">Markdown</option><option value="objective-c" mode="clike">Objective-C</option><option value="php" mode="php">PHP</option><option value="perl" mode="perl">Perl</option><option value="python" mode="python">Python</option><option value="r" mode="r">R</option><option value="rst" mode="rst">reStructedText</option><option value="ruby" mode="ruby">Ruby</option><option value="sql" mode="sql">SQL</option><option value="sass" mode="sass">SASS/SCSS</option><option value="shell" mode="shell">Shell</option><option value="scala" mode="clike">Scala</option><option value="swift" mode="clike">Swift</option><option value="vb" mode="vb">VB/VBScript</option><option value="xml" mode="text/xml">XML</option><option value="yaml" mode="yaml">YAML</option><option value="other">其他语言</option></select></p>
<textarea rows="10" cols="40" name="insert-codeblock" placeholder="这里填入代码块"></textarea>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let PLan = $('#select-code option:selected').val();
    let codeblock = $('textarea[name="insert-codeblock"]').val();
    insertAtCursor('\n```'+PLan+'\n'+codeblock+'\n```\n');
});
});
// 隐藏内容
$("#b-wmd-reply").on("click",function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 38%;" role="dialog">
    <div><p><b>插入隐藏内容</b></p></div>
  <form>
<textarea rows="10" cols="40" name="insert-hide" placeholder="这里填入需要隐藏的内容"></textarea>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let hide = $('textarea[name="insert-hide"]').val();
    insertAtCursor('\n[hide]'+hide+'[/hide]\n');
});
});


$("#b-wmd-delete").on("click",function() {
	insertAtCursor(' ~~ 删除线效果 ~~ ');
});
$("#b-wmd-table").on("click",function() {
	insertAtCursor('\n表头|表头|表头\n---|:--:|---:\n居左|居中|居右\n居左|居中|居右\n');
});
$("#b-wmd-tabs").on("click", function() {
    insertAtCursor('\n[tabs]\n [tab-pane label="Tab标题"]Tab内容[/tab-pane]\n [tab-pane label="Tab标题"]Tab内容[/tab-pane]\n[/tabs]\n');
});
// 按钮
$("#b-wmd-btn").on("click", function() {
$("#ui-datepicker-div").after(`
<div class="wmd-prompt-dialog"style="top: 38%;"role="dialog"><div><p><b>插入按钮</b></p></div><form><p>颜色：<select id="select-color"><option selected="selected"value="">灰色(默认)</option><option value="blue">蓝色</option><option value="pink">粉色</option><option value="red">红色</option><option value="purple">紫色</option><option value="orange">橙色</option><option value="green">绿色</option></select><br/>类型：<select id="select-type"><option selected="selected"value="">默认</option><option value="outline">outline</option><option value="block">块形</option><option value="larger">大的</option><option value="block larger">大块</option><option value="outline larger">大outline</option></select><br/>位置：<select id="select-position"><option selected="selected"value="">左边(默认)</option><option value="center">居中</option><option value="right">右边</option></select></p>链接：<input type="text"name="insert-href">标题：<input type="text"name="insert-contents">图标(可留空)：<input type="text"name="insert-ico"><button type="button"class="btn btn-s primary btn-ok">确定</button><button type="button"class="btn btn-s btn-cancel">取消</button></form></div>`);
$(".btn-ok").click(function(){
    let color = $('#select-color option:selected').val();
    let position = $('#select-position option:selected').val();
    let type = $('#select-type option:selected').val();
    let href = $('input[name="insert-href"]').val();
    let contents = $('input[name="insert-contents"]').val();
    let ico = $('input[name="insert-ico"]').val();
    insertAtCursor('\n[btn href="'+href+'" type="'+type+' '+color+' '+position+'" ico="'+ico+'"]'+contents+'[/btn]\n');
});
});
// note
$("#b-wmd-note").on("click", function() {
$("#ui-datepicker-div").after(`
<div class="wmd-prompt-dialog"style="top: 38%;"role="dialog"><div><p><b>插入note</b></p></div><form><p>类型：<select id="select-type"><option selected="selected"value="">无</option><option value="default ">默认</option><option value="primary ">主要的</option><option value="success ">成功</option><option value="info ">信息</option><option value="warning ">警告</option><option value="danger ">危险</option></select><br/>状态：<select id="select-flat"><option selected="selected"value="flat">flat(默认)</option><option value="modern">modern</option><option value="disabled">disabled</option></select><br/>图标：<select id="select-ico"><option selected="selected"value="">有图标(默认)</option><option value=" no-icon">无图标</option></select></p>内容：<textarea rows="5" cols="40" name="insert-contents"placeholder="内容"></textarea><button type="button"class="btn btn-s primary btn-ok">确定</button><button type="button"class="btn btn-s btn-cancel">取消</button></form></div>`);
$(".btn-ok").click(function(){
    let flat = $('#select-flat option:selected').val();
    let type = $('#select-type option:selected').val();
    let hasico = $('#select-ico option:selected').val();
    let contents = $('textarea[name="insert-contents"]').val();
    insertAtCursor('\n[note type="'+type+''+flat+''+hasico+'"]'+contents+'[/note]\n');
});    
});
// note-ico
$("#b-wmd-note-ico").on("click", function() {
$("#ui-datepicker-div").after(`
<div class="wmd-prompt-dialog"style="top: 38%;"role="dialog"><div><p><b>插入note</b></p></div><form><p>颜色：<select id="select-color"><option selected="selected"value="">灰色(默认)</option><option value="blue ">蓝色</option><option value="pink ">粉色</option><option value="red ">红色</option><option value="purple ">紫色</option><option value="orange ">橙色</option><option value="green ">绿色</option></select><br/>状态：<select id="select-flat"><option selected="selected"value="flat">flat(默认)</option><option value="modern">modern</option><option value="disabled">disabled</option></select><br/></p>图标：<input type="text"name="insert-ico">内容：<textarea rows="5" cols="40" name="insert-contents"placeholder="内容"></textarea><button type="button"class="btn btn-s primary btn-ok">确定</button><button type="button"class="btn btn-s btn-cancel">取消</button></form></div>`);
$(".btn-ok").click(function(){
    let flat = $('#select-flat option:selected').val();
    let color = $('#select-color option:selected').val();
    let ico = $('input[name="insert-ico"]').val();
    let contents = $('textarea[name="insert-contents"]').val();
    insertAtCursor('\n[note-ico type="'+color+''+flat+'" ico="'+ico+'"]'+contents+'[/note-ico]\n');
});     
});
// 切换隐藏
$("#b-wmd-hide-toggle").on("click", function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 38%;" role="dialog">
    <div><p><b>切换隐藏(hide-toggle)</b></p></div>
  <form>
标题：<input type="text" name="insert-title" placeholder="标题/提示">
<textarea rows="10" cols="40" name="insert-contents" placeholder="这里填入需要隐藏的内容"></textarea>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>`);
$(".btn-ok").click(function(){
    let title = $('input[name="insert-title"]').val();
    let contents = $('textarea[name="insert-contents"]').val();
	insertAtCursor('\n[hide-toggle name="'+title+'"]'+contents+'[/hide-toggle]\n');
});
});
// 行内隐藏
$("#b-wmd-hide-inline").on("click", function() {
    $("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 38%;" role="dialog">
    <div><p><b>插入行内隐藏</b></p></div>
  <form>
标题：<input type="text" name="insert-title" placeholder="标题/提示">
<textarea rows="10" cols="40" name="insert-contents" placeholder="这里填入需要隐藏的内容"></textarea>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>`);
$(".btn-ok").click(function(){
    let title = $('input[name="insert-title"]').val();
    let contents = $('textarea[name="insert-contents"]').val();
	insertAtCursor('\n[hide-inline name="'+title+'"]'+contents+'[/hide-inline]\n');
});
});
// 块内隐藏
$("#b-wmd-hide-block").on("click", function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 38%;" role="dialog">
    <div><p><b>插入隐藏块(占用一行)</b></p></div>
  <form>
标题：<input type="text" name="insert-title" placeholder="标题/提示">
<textarea rows="10" cols="40" name="insert-contents" placeholder="这里填入需要隐藏的内容"></textarea>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>`);
$(".btn-ok").click(function(){
    let title = $('input[name="insert-title"]').val();
    let contents = $('textarea[name="insert-contents"]').val();
	insertAtCursor('\n[hide-block name="'+title+'"]'+contents+'[/hide-block]\n');
});    
});
// 原生h5
$("#b-wmd-nhtml").on("click", function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" style="top: 38%;" role="dialog">
    <div><p><b>插入html代码</b></p></div>
  <form>
<textarea rows="10" cols="40" name="insert-html" placeholder="这里填入html代码"></textarea>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>`);
$(".btn-ok").click(function(){
    let htmlcode = $('textarea[name="insert-html"]').val();
    insertAtCursor('\n!!!\n'+htmlcode+'\n!!!\n');
});
});

// 标题插入
$("#b-wmd-title").on("click", function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" role="dialog">
    <div><p><b>标题插入</b></p></div>
<form>
标题选择：<select id="select-title"><option selected="selected"value="#">h1(默认)</option><option value="##">h2</option><option value="###">h3</option><option value="####">h4</option><option value="#####">h5</option><option value="######">h6</option></select><br/>
<input type="text" name="my-title" placeholder="标题名">
<button type="button" class="btn btn-s primary btn-ok">确定</button>
<button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>`);
$(".btn-ok").click(function(){
    let titleType = $('#select-title option:selected').val();
    let getTitle = $('input[name="my-title"]').val();
    insertAtCursor(''+titleType+''+getTitle+'\n');
});
});

// 复选框
$("#b-wmd-wcheakbox").on("click", function() {
    $("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" role="dialog">
    <div><p><b>插入复选框(主题需要开启魔改美化)</b></p></div>
  <form>
<p>颜色选择：<select id="select-color"><option selected="selected" value="blue">蓝色(默认)</option><option value="red">红色</option><option value="yellow">黄色</option><option value="green">绿色</option></select><br>
勾选类型：<select id="select-type"><option selected="selected" value="">勾选(默认)</option><option value="minus">-选</option></select><br>是否勾选：<select id="select-check"><option selected="selected" value="checked">是(默认)</option><option value="">否</option></select></p>
    <input type="text" name="insert-contents" value="">
    <input type="text" style="display:none;">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let color = $('#select-color option:selected').val();
    let type = $('#select-type option:selected').val();
    let checked = $('#select-check option:selected').val();
    let contents = $('input[name="insert-contents"]').val();
	insertAtCursor('\n[cb type="'+color+' '+type+'" checked="'+checked+'"]'+contents+'[/cb]\n');    
});
});
// 行内标签
$("#b-wmd-inline-tag").on("click", function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" role="dialog">
    <div><p><b>插入行内标签(主题需要开启魔改美化)</b></p></div>
  <form>
<p>颜色选择：<select id="select-color"><option selected="selected"value="grey">灰色(默认)</option><option value="blue">蓝色</option><option value="red">红色</option><option value="yellow">黄色</option><option value="green">绿色</option></select></p>
    <input type="text" name="insert-contents" value="">
    <input type="text" style="display:none;">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let color = $('#select-color option:selected').val();
    let contents = $('input[name="insert-contents"]').val();
    insertAtCursor(' [in-tag color="'+color+'"]'+contents+'[/in-tag] ');
});
});
// 单选框
$("#b-wmd-radio").on("click", function() {
    $("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" role="dialog">
    <div><p><b>插入单选框(主题需要开启魔改美化)</b></p></div>
  <form>
<p>颜色选择：<select id="select-color"><option selected="selected"value="blue">蓝色(默认)</option><option value="red">红色</option><option value="yellow">黄色</option><option value="green">绿色</option></select><br>是否选中：<select id="select-check"><option selected="selected" value="checked">是(默认)</option><option value="">否</option></p>
    <input type="text" name="insert-contents" value="">
    <input type="text" style="display:none;">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let color = $('#select-color option:selected').val();
    let checked = $('#select-check option:selected').val();
    let contents = $('input[name="insert-contents"]').val();
    insertAtCursor('\n[radio color="'+color+'" checked="'+checked+'"]'+contents+'[/radio]\n');
});
});
$("#b-wmd-md-submit").on("click", function() {
	$("#btn-submit").click()
});
$("#b-wmd-md-draft").on("click", function() {
	$("#btn-save").click()
});
$("#b-wmd-md-explain").on("click", function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog" role="dialog">
    <div><p><b>说明</b></p></div>
    <hr>
    <p>这是一款兼容typecho原ui的md编辑器</p>
    <p>采用typecho同款弹窗生成器</p>
    <p>未来将更新更多内容....</p>
    <p>如有bug或者建议欢迎去<a href="https://github.com/wehaox/Typecho-Butterfly/issues">GitHub</a>向我反馈或者加群:218796706</p>
    <p>有GitHub的同学帮忙点个star，谢谢了🌹</p>
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
});
// 文字高亮
$("#b-wmd-mark").on("click",function() {
$("#ui-datepicker-div").after(`
    <div class="wmd-prompt-dialog">
    <div><p><b>插入高亮文字</b></p></div>
  <form>
<p>颜色选择：<select id="select-color"><option selected="selected"value="default">灰色(默认)</option><option value="blue">蓝色</option><option value="pink">粉色</option><option value="red">红色</option><option value="purple">紫色</option><option value="orange">橙色</option><option value="green">绿色</option></select></p>
    <input type="text" name="mark">
    <input type="text" style="display:none;">
    <button type="button" class="btn btn-s primary btn-ok">确定</button>
    <button type="button" class="btn btn-s btn-cancel">取消</button></form>
</div>
`);
$(".btn-ok").click(function(){
    let color = $('#select-color option:selected').val();
    let contents = $('input[name="mark"]').val();
    insertAtCursor(' [label color="'+color+'"]'+contents+'[/label] ');
});
});
$("#b-wmd-linecode,#b-wmd-code,#b-wmd-reply,#b-wmd-nhtml,#b-wmd-wcheakbox,#b-wmd-inline-tag,#b-wmd-radio,#b-wmd-md-link,#b-wmd-md-img,#b-wmd-mark,#b-wmd-btn,#b-wmd-note,#b-wmd-hide-block,#b-wmd-hide-inline,#b-wmd-hide-toggle,#b-wmd-note-ico,#b-wmd-md-explain,#b-wmd-title").click(function(){
var y = document.createElement("div");
let height = document.body.scrollHeight;
z = y.style;
y.className = "wmd-prompt-background";
z.position = "absolute";
z.top = "0";
z.zIndex = "1000";
z.opacity = "0.5";
z.width = "100%";
z.height = height + "px";
z.left = "0";
document.body.appendChild(y);
$(".btn-cancel,.btn-ok").click(function(){$(".wmd-prompt-dialog,.wmd-prompt-background").remove();});
});
var text = document.getElementById('text');
    var insert = document.getElementById('insert');
    function insertAtCursor(myValue , myField = $('#text')[0]) {
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
            sel.select();
        } else if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            var beforeValue = myField.value.substring(0, startPos);
            var afterValue = myField.value.substring(endPos, myField.value.length);
            myField.value = beforeValue + myValue + afterValue;
            myField.selectionStart = startPos + myValue.length;
            myField.selectionEnd = startPos + myValue.length;
            myField.focus();
        } else {
            myField.value += myValue;
            myField.focus();
        }
    }
 })