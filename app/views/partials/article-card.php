<style>
    .card {
    box-sizing: border-box;
    width: 79vw;
    height: 80vw;
    background-color: #89ECBC;
    border: 1px solid white;
    box-shadow: 12px 17px 51px rgba(0, 0, 0, 0.22);
    backdrop-filter: blur(6px);
    border-radius: 17px;
    text-align: center;
    cursor: pointer;
    transition: all 0.5s;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
    color: black;
    padding: 5%;
    }
      .card p{font-size: calc(5px + 1vw);}
      .card h2{font-weight: bold; font-size: calc(10px + 1vw);}
      
    .card:hover {
    border: 1px solid black;
    transform: scale(1.05);
    }

    .card:active {
    transform: scale(0.95) rotateZ(1.7deg);
    }
    .card .card-image{
    width: 100%;
    height: 100%;

    background: url(assets/resources/5.jpg);
    background-size: cover;
    background-position: center;
  }
  
</style>
<div class="article-card">
  <div class="card" style="overflow: hidden;">
    <div class="card-image" style="margin-bottom: 2%; border-radius: 5px;"></div>
      <h2>THE MOST SKIBIDI KITTEN</h5>
      <p>
        それ以来、彼女と私は二度と会うことはありませんでした。ほら、ようやく分かりました。
        私があの映画を何度も撮り直した理由。 
        <br>
        「ちょっとファンタジーが足りないと思いませんか？」
      </p>
  </div>
</div>