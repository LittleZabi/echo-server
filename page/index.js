const handleShowChilds = async (id, element) => {
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get("./op.php?show-childs=1&id=" + id)
    .then((e) => {
      element.innerHTML = "Show Childs";
      element.disabled = false;
      if (e.data === "") {
        alert("No Childs Found...");
      } else {
        dataList(e.data);
      }
    })
    .catch((e) => {
      element.innerHTML = "Show Childs";
      element.disabled = false;
      console.error(e.message);
    });
};
const dataList = (data) => {
  // data = JSON.parse(data);
  let html = `<div class="wrap">
  <span class="close" onclick="closeModal()">&times;</span>
  <div class="super-container">
        <div class="form" style="min-width: 400px;">
            <h3>Childs informations</h3>`;
  let list = "";
  data.map((e) => {
    list += `
            <div class="form-group mx-sm-3 mb-2 item">
                <label for="filename" id="file" style="text-align: left;display:block;font-size: 14px">current filename: <b>${e.new_filename}</b></label>
                <a href="./view.php?slug=${e.token}"><b>Open File Request Page.</b></a>
                <br/>
                <br/>
                
                <input type="text" id="newName${e.id}" value='${e.new_filename}' class="form-control" placeholder="Enter child name here...">
                <br/>
                <button onclick="changeName(${e.id}, this)" type="submit" name="submit-link" class="btn btn-primary" style="width: 200px;margin:auto;">update name</button>
                </div>
              
            <br>
        `;
  });
  data.map((e) => {
    list += `
            <div class="form-group mx-sm-3 mb-2 item">
                <label for="filename" id="file" style="text-align: left;display:block;font-size: 14px">current filename: <b>${e.new_filename}</b></label>
                <a href="./view.php?slug=${e.token}"><b>Open File Request Page.</b></a>
                <br/>
                <br/>
                
                <input type="text" id="newName${e.id}" value='${e.new_filename}' class="form-control" placeholder="Enter child name here...">
                <br/>
                <button onclick="changeName(${e.id}, this)" type="submit" name="submit-link" class="btn btn-primary" style="width: 200px;margin:auto;">update name</button>
                </div>

            <br>
        `;
  });
  html += list;
  html += `</div>
    </div>
    </div>
    `;
  const modal = document.querySelector("#modal");
  modal.innerHTML = html;
  modal.style.display = "flex";
};
const changeName = async (id, element) => {
  const newName = document.querySelector("#newName" + id);
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`./op.php?update-name=1&id=${id}&to=${newName.value}`)
    .then((e) => {
      element.innerHTML = "Add";
      element.disabled = false;
      alert("name change successfuly to " + newName.value);
      try {
        element.parentNode.childNodes[1].innerHTML = `current filename: <b>${newName.value}</b>`;
      } catch (error) {
        console.error(error.message);
      }
    })
    .catch((e) => {
      element.innerHTML = "Add";
      element.disabled = false;
      console.log(e.message);
    });
};
const handleAddNewChild = async (id, element) => {
  const modal = document.querySelector("#modal");
  const html = `
    <div class="wrap">
    <span class="close" onclick="closeModal()">&times;</span>
        <div class="form" style="min-width: 400px;">
            <h3>Enter Child Details</h3>
            <div class="form-group mx-sm-3 mb-2">
                <label for="filename" style="text-align: left;display:block;font-size: 14px">Enter child name</label>
                <input type="text" id="filename" class="form-control" placeholder="Enter child name here...">
            </div>
            <br>
            <button onclick="handleAdd(${id}, this)" type="submit" name="submit-link" class="btn btn-primary" style="width: 200px;margin:auto;">Add</button>
        </div>
    </div>
    `;
  modal.innerHTML = html;
  modal.style.display = "flex";
};
const handleAdd = async (id, element) => {
  const filename = document.querySelector("#filename");
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`./op.php?add-child=1&id=${id}&filename=${filename.value}`)
    .then((e) => {
      element.innerHTML = "Add";
      element.disabled = false;

      if (e.data === "exist") {
        alert("file name already exist choose another one");
      }
      if (e.data === "success") {
        alert("successfully added...");
      }
      closeModal();
    })
    .catch((e) => {
      element.innerHTML = "Add";
      element.disabled = false;
      console.log(e.message);
    });
};
const closeModal = () => {
  const modal = document.querySelector("#modal");
  modal.style.display = "none";
};
