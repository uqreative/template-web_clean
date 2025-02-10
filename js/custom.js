$(document).ready(function () {
    snbBtn();
});
function snbBtn() {
    const pgTitle = $('.title-group .title');
    const pgParent = $('.title-group .eng');
    const pgText = $('[data-gnb="2"].isVisiting').first().text();
    const pgPage = $('[data-gnb="1"].isVisiting').first().text();
    pgTitle.prepend(pgText);
    pgParent.prepend(pgPage);
}