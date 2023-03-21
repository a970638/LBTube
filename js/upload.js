
document.getElementById("submit-button").addEventListener("click", function(event) {
    if (document.getElementById("UnitSelect").value == '請選擇單元') {
        alert('請先選擇單元');
    }else{
        if(!document.getElementById("fileInput").value == false){
            document.getElementById("loadingOverlay").style.display = "flex";
        }
    }
});

document.getElementById("SemesterSelect").addEventListener("change", function() {
    var selectedValue = this.value;
  
    // 根据选择的值更改第二个下拉菜单的选项列表
    var UnitSelect = document.getElementById("UnitSelect");
    UnitSelect.innerHTML = "";
    if (selectedValue === "五上") {
        UnitSelect.innerHTML = "<option>請選擇單元</option><option>小數（小數加減、小數乘除）</option><option>乘法和除法（多位數乘除）</option><option>時間的乘除</option><option>四則運算</option><option>因數與公因數</option><option>倍數與公倍數</option><option>擴分、約分和通分</option><option>異分母分數的加減</option><option>平面圖形（三角形的邊角關係、正多邊形、扇形和圓心角）</option><option>面積（平行四邊形、三角形、梯形面積）</option><option>線對稱圖形</option><option>柱體、錐體和球</option>";
    }else if(selectedValue === "五下") {
        UnitSelect.innerHTML = "<option>請選擇單元</option><option>分數（分數的乘除）</option><option>長方體和正方體的體積</option><option>容積</option><option>表面積</option><option>扇形</option><option>立體形體（柱體、錐體與球體）</option><option>小數（小數的乘除）</option><option>符號代表數（含有未知數之加減與乘除）</option><option>比率與百分率</option><option>生活中的大單位（公噸、公畝、公頃、平方公里）</option><option>時間的計算</option>";
    }
  });

document.getElementById("fileInput").addEventListener("change", function() {
    var preview = document.getElementById("preview");
    preview.src = URL.createObjectURL(this.files[0]);
});
