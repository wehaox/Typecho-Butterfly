/* 增加自定义功能 */
const items = [
    {
        title: '回复可见',
        id: 'wmd-hide-button',
        svg: '<svg t="1612402690962" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="15751" width="20" height="20"><path d="M554.666667 438.101333V277.333333h-85.333334v160.768L330.112 357.717333l-42.666667 73.898667L426.666667 512l-139.221334 80.384 42.666667 73.898667L469.333333 585.898667V746.666667h85.333334v-160.768l139.221333 80.384 42.666667-73.898667L597.333333 512l139.221334-80.384-42.666667-73.898667L554.666667 438.101333z" p-id="15752" fill="#9b9b9b"></path></svg>',
        text: '\n[@hide]这里的内容回复后才能看见[/hide]\n'
    }
];

items.forEach(_ => {
    let item = $(`<li class="wmd-button" id="${_.id}" title="${_.title}">${_.svg}</li>`);
    item.on('click', function () {
        $('#text').insertContent(_.text);
    });
    $('#wmd-button-row').append(item);
});