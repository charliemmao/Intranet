<HTML>
<HEAD>
<SCRIPT LANGUAGE=JavaScript>

if (!(document.all))
{
   if (document.layers)
   {
      window.location.replace("NNMenus.htm");
   }
   else
   {
      window.location.replace("NoMenus.htm");
   }
}


var woodMenuItems = new Array();
woodMenuItems[0] = new Array();

woodMenuItems[0][0] = "Oak";
woodMenuItems[0][1] = "OakWood.htm";
woodMenuItems[0][2] = "Oak Timber";

woodMenuItems[1] = new Array();
woodMenuItems[1][0] = "Teak";
woodMenuItems[1][1] = "TeakWood.htm";
woodMenuItems[1][2] = "Teak Timber";

woodMenuItems[2] = new Array();
woodMenuItems[2][0] = "Pine";
woodMenuItems[2][1] = "PineWood.htm";
woodMenuItems[2][2] = "Pine Timber";

woodMenuItems[3] = new Array();
woodMenuItems[3][0] = "Yew";
woodMenuItems[3][1] = "YewWood.htm";
woodMenuItems[3][2] = "Yew Timber";

// Second menu
var metalMenuItems = new Array();
metalMenuItems[0] = new Array();

metalMenuItems[0][0] = "Steel";
metalMenuItems[0][1] = "SteelMetal.htm";
metalMenuItems[0][2] = "Steel Girders";

metalMenuItems[1] = new Array();
metalMenuItems[1][0] = "Copper";
metalMenuItems[1][1] = "CopperMetal.htm";
metalMenuItems[1][2] = "Copper Pipes";

metalMenuItems[2] = new Array();
metalMenuItems[2][0] = "Gold";
metalMenuItems[2][1] = "GoldMetal.htm";
metalMenuItems[2][2] = "Gold Ingots";

var bricksMenuItems = new Array();
bricksMenuItems[0] = new Array();

bricksMenuItems[0][0] = "StdHouse";
bricksMenuItems[0][1] = "StdHousebricks.htm";
bricksMenuItems[0][2] = "Standard House Brick";

bricksMenuItems[1] = new Array();
bricksMenuItems[1][0] = "LargeHouseBrick";
bricksMenuItems[1][1] = "LargeHousebricks.htm";
bricksMenuItems[1][2] = "Large House Bricks";

bricksMenuItems[2] = new Array();
bricksMenuItems[2][0] = "BreezeBlock";
bricksMenuItems[2][1] = "BreezeBlock.htm";
bricksMenuItems[2][2] = "Breeze Block";

function createMenu(menuName, menuItems)
{
   var divHTML = '<DIV ID="' + menuName + 'MenuDiv" CLASS="DivMenu"';
   divHTML = divHTML + ' onmouseout="return hideMenu(this)">';

   var tableHTML = '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1 ID="' 
      + menuName + 'Table">';
   var tableRowHTML = "";
   var rowCount;
   var totalNoRows = menuItems.length;
   for (rowCount = 0; rowCount < totalNoRows; rowCount++)
   {
      tableRowHTML = tableRowHTML + '<TR><TD ID="' + 
         menuName + menuItems[rowCount][0] + 
        '" RollOver RollOut';
      tableRowHTML = tableRowHTML + ' onclick="goPage(\'' 
         + menuItems[rowCount][1] + '\')"';
      tableRowHTML = tableRowHTML 
         + 'CLASS="TDMenu">' + menuItems[rowCount][2] 
         + '</TD></TR>';
   }

   return divHTML + tableHTML + tableRowHTML + '</TABLE></DIV>';
}


function showMenu(menuToShow)
{
   var srcElement = event.srcElement;
   var xPos = parseInt(srcElement.offsetLeft);
   var yPos = parseInt(srcElement.offsetTop);

   menuToShow.style.left = xPos + (srcElement.width)
   menuToShow.style.top = yPos;
}

function hideMenu(menuToHide)
{
   if (event.toElement != menuToHide &&
      menuToHide.contains(event.toElement) == false)
   {
      menuToHide.style.left = -200;
      menuToHide.style.top = -1000;
   }
}

function document_onmouseover() 
{
   var srcElement = event.srcElement;

   if (srcElement.tagName == "TD" && typeof(srcElement.RollOver) != "undefined")
   {
      srcElement.style.color = "white";
      srcElement.style.backgroundColor ="darkblue";
   }
}

function document_onmouseout() 
{
   var srcElement = event.srcElement;
   if (srcElement.tagName == "TD" && typeof(srcElement.RollOut) != "undefined")
   {
      srcElement.style.color = "darkblue";
      srcElement.style.backgroundColor = "darkorange";
   }
}

function goPage(src)
{
   window.location.href = src;
}

document.onmouseover = document_onmouseover;
document.onmouseout = document_onmouseout;
</SCRIPT>

<STYLE>
   .DivMenu {position:absolute;
      left:-200;
      top:-1000;
      width:180;
      z-index:100;
      background-color:darkorange;
      border: 4px groove lightgrey;
   }

   .TDMenu {
      color:darkblue;
      font-family:verdana;
      font-size:70%;
      width:100%;
      cursor:default;
   }
</STYLE>
</HEAD>

<BODY>
<SCRIPT LANGUAGE=JavaScript>
   document.write(createMenu('Wood', woodMenuItems))
   document.write(createMenu('Metal', metalMenuItems))
   document.write(createMenu('Bricks', bricksMenuItems))
</SCRIPT>


<IMG ID="WoodMenuImage"
   SRC="../../../images/WoodButton.gif" 
   STYLE="position:absolute;left:10;top:75"
   onmouseover="return showMenu(WoodMenuDiv)"
   onmouseout="return hideMenu(WoodMenuDiv)">
<IMG ID="MetalMenuImage"
   SRC="../../../images/MetalButton.gif" 
   STYLE="position:absolute;left:10;top:115"
   onmouseover="return showMenu(MetalMenuDiv)"
   onmouseout="return hideMenu(MetalMenuDiv)">

<IMG ID="BricksMenuImage"
   SRC="../../../images/BricksButton.gif" 
   STYLE="position:absolute;left:10;top:155"
   onmouseover="return showMenu(BricksMenuDiv)"
   onmouseout="return hideMenu(BricksMenuDiv)">

</BODY>
</HTML>
