const ROOT_OP = "./modules/op.php";
const removeActive = () => {
  try {
    const k = document.querySelectorAll(".list-group .active");

    if (k.length > 0) {
      k.forEach((e) => {
        e.classList.remove("active");
      });
    }
  } catch (error) {
    console.log(error.message);
  }
};
const handleShowChilds = async (id, parent, element) => {
  removeActive();
  element.classList.add("active");
  const loading = document.querySelector("#show-loading");
  loading.innerHTML = "Loading...";
  loading.style.display = "block";
  await axios
    .get(`${ROOT_OP}?show-childs=1&id=${id}`)
    .then((e) => {
      loading.style.display = "none";
      if (e.data === "") {
        loading.innerHTML = "No Data found!";
        loading.style.display = "block";
        dataList([], parent);
      } else {
        dataList(e.data, parent);
      }
    })
    .catch((e) => {
      loading.style.display = "none";
      console.error(e.message);
    });
};
const handleSearch = async (input) => {
  const loading = document.querySelector("#item-list");
  if (input.value.length > 0) {
    loading.innerHTML = "Searching...";
    loading.style.display = "block";
    await axios
      .get(`${ROOT_OP}?search=1&q=${input.value}`)
      .then((e) => {
        if (e.data === "") {
          loading.innerHTML = "No Data found!";
          loading.style.display = "block";
          SearchResult(false);
        } else {
          console.log(e.data);
          SearchResult(e.data);
        }
      })
      .catch((e) => {
        loading.style.display = "none";
        console.error(e.message);
      });
  } else {
    SearchResult(filesList);
  }
};

SearchResult = (data) => {
  const view = document.querySelector("#item-list");
  if (data && data.length > 0) {
    html = "";
    data.map((e) => {
      html += `
        <span onclick="handleShowChilds(${e.id},'${
        e.filename === "" ? e.base_url : e.filename
      }', this)" class="${
        e.complete == 1 ? "done" : ""
      } list-group-item list-group-item-action flex-column align-items-start">
                      <div class="d-flex w-100 justify-content-between">
                          <h5 class="mb-1">${
                            e.filename === "" ? e.base_url : e.filename
                          }</h5>
                          <small>${new Date(e.createdAt).toDateString()}</small>
                      </div>
                      <p class="mb-1">${e.base_url}</p>
                      <p class="mb-1"><b>Final: </b>${
                        e.finalLink == "" ? "There is not output" : e.finalLink
                      }</p>
                      <p class="mb-1"><b>Bot Operation: ${
                        e.referToBot == 1
                          ? " Bot is processing on it"
                          : "Not Refer to Bot!"
                      }</b> </p>
                      <span><button class="btn btn-primary" onclick="handleAddNewChild(${
                        e.id
                      }, this)">Add Child</button></span>
                  </span>
        `;
    });
    view.innerHTML = html;
  } else {
    view.innerHTML = "<br><br/><h4>Not found</h4><br><br/>";
  }
};
const dataList = (data, parent) => {
  const view = document.querySelector("#item-view");
  if (data.length < 1) {
    view.innerHTML = "";
    return 1;
  }
  // data = JSON.parse(data);
  let html = `<div class="wrap">
    <div class="super-container">
          <div class="form" style="min-width: 400px;">
              <h3>${parent} childs</h3>`;
  let list = "";
  data.map((e) => {
    list += `
              <div class="form-group mx-sm-3 mb-2 item">
                  <label for="filename" id="file" style="text-align: left;display:block;font-size: 14px"><h4>${e.new_filename}</h4></label>
                  <a href="./view.php?slug=${e.token}"><b>Open File Request Page.</b></a>
                  <br/>
                  <br/>
                  
                  <input type="text" id="newName${e.id}" value='${e.new_filename}' class="form-control" placeholder="Enter child name here...">
                  <br/>
                  <button onclick="changeName(${e.id}, this)" type="submit" name="submit-link" class="btn btn-primary" style="width: 200px;margin:auto;">update name</button>
                  <button onclick="handleDelete(${e.id}, this)" type="submit" name="submit-link" class="btn btn-danger" style="width: 200px;margin:auto;">Delete</button>
                  </div>
                
              <br>
          `;
  });
  html += list;
  html += `</div>
      </div>
      </div>
      `;
  view.innerHTML = html;
};
const changeName = async (id, element) => {
  const newName = document.querySelector("#newName" + id);
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`${ROOT_OP}?update-name=1&id=${id}&to=${newName.value}`)
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
const handleDelete = async (id, element) => {
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`${ROOT_OP}?delete-child=1&id=${id}`)
    .then((e) => {
      element.innerHTML = "Delete";
      element.disabled = false;
      alert("Item deleted successfully!");
      try {
        element.parentNode.style.display = "none";
      } catch (error) {
        console.log(error.message);
      }
    })
    .catch((e) => {
      element.innerHTML = "Delete";
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
    .get(`${ROOT_OP}?add-child=1&id=${id}&filename=${filename.value}`)
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
const addNewFileInList = async () => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Add new file";
    let form = document.querySelector(".modal-body form");
    let html = `
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Enter filename.</label>
        <input type="text" class="form-control" id="filename">
        <label for="recipient-name" class="col-form-label">Enter base url.</label>
        <input type="text" class="form-control" id="fileurl">
        <label for="recipient-name" class="col-form-label">Enter cache name.</label>
        <input type="text" value="Featured" class="form-control" id="filecache">
    </div>
    `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="handleAddNew(this)">Add File</button>
    `;
    buttons.innerHTML = html;
  } catch (error) {
    console.log(error.message);
  }
};
const closeModal = () => {
  const modal = document.querySelector("#modal");
  modal.style.display = "none";
};

const handleAddNew = (element) => {
  let c = document.querySelector("#filecache").value;
  let f = document.querySelector("#filename").value;
  let u = document.querySelector("#fileurl").value;
  if (f === "") {
    alert("Enter filename.");
    return;
  }
  if (u === "") {
    alert("Enter file base url.");
    return;
  }

  $.ajax({
    url: ROOT_OP,
    method: "GET",
    data: {
      "submit-link": 1,
      "file-link": u,
      "file-name": f,
      "cache-type": c === "" ? "Featured" : c,
    },
    success: (e) => {
      let res = JSON.parse(e);
      console.log(res);
      if (res.message == "exist") {
        window.alert(
          `File is already exist with ID ${res.id} check in files list.`
        );
      }
      if (res.message == "success") {
        window.location.href = window.location.href;
      }
    },
  });
};
const handleSaveEdits = (element) => {
  let i = document.querySelector("#fileID").value;
  let c = document.querySelector("#filecache").value;
  let f = document.querySelector("#filename").value;
  let u = document.querySelector("#fileurl").value;
  let o = document.querySelector("#final-link").value;
  element.innerHTML = "loading...";
  element.disabled = true;
  if (f === "") {
    alert("Enter filename.");
    return;
  }
  if (u === "") {
    alert("Enter file base url.");
    return;
  }

  $.ajax({
    url: ROOT_OP,
    method: "POST",
    data: {
      "edit-link": 1,
      "file-link": u,
      "file-name": f,
      "final-link": o,
      "cache-type": c === "" ? "Featured" : c,
      id: i,
    },
    success: (e) => {
      element.innerHTML = "Save changes";
      element.disabled = true;
      if (e == "success") {
        window.location.href = window.location.href;
      }
    },
    onerror: (e) => {
      element.innerHTML = "Save changes";
      element.disabled = true;
      alert(e.message);
    },
  });
};
const handleEditFile = (item) => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Edit file";
    let form = document.querySelector(".modal-body form");
    let html = `
        <div class="form-group">
            <label  class="col-form-label">Update filename.</label>
            <input value="${item.id}" type="hidden" id="fileID">
            <input value="${item.filename}" type="text" class="form-control" id="filename">
            <label  class="col-form-label">Update base url.</label>
            <input value="${item.base_url}" type="text" class="form-control" id="fileurl">
            <label class="col-form-label">Update Output link.</label>
            <input value="${item.finalLink}" type="text" class="form-control" id="final-link">
            <label class="col-form-label">Update cache name.</label>
            <input value="${item.cache}" type="text" class="form-control" id="filecache">
        </div>
        `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="handleSaveEdits(this)">Save changes</button>
        `;
    buttons.innerHTML = html;
  } catch (error) {}
};
const addChildModal = (id) => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Add new child";
    let form = document.querySelector(".modal-body form");
    let html = `
        <div class="form-group">
        <input type="hidden" value=${id} id="fileID">
            <label  class="col-form-label">Enter child name.</label>
            <input type="text" class="form-control" id="filename">
        </div>
        `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="handleAddChild(this)">Add child</button>
        `;
    buttons.innerHTML = html;
  } catch (error) {}
};
const handleEditChild = (item) => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Edit child";
    let form = document.querySelector(".modal-body form");
    let html = `
        <div class="form-group">
            <input type="hidden" value=${item.id} id="fileID">
            <input type="hidden" value=${item.parentID} id="pid">
            <label  class="col-form-label">Change child name.</label>
            <input type="text" value="${item.filename}" class="form-control" id="filename">
            <label  class="col-form-label">Change parent name.</label>
            <input type="text" value="${item.parentFileName}" class="form-control" id="parent-name">
            <label  class="col-form-label">Update final link.</label>
            <input type="text" value="${item.finalLink}" class="form-control" id="final-link">
            <label  class="col-form-label">Update gdrive rename link.</label>
            <input type="text" value="${item.Rename}" class="form-control" id="renamed">
        </div>
        `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveChildChanges(this)">save changes</button>
        `;
    buttons.innerHTML = html;
  } catch (error) {}
};
const handleDeleteFile = (id) => {
  let k = confirm("Do you want to Delete! click ok to delete.");
  if (k) {
    $.ajax({
      url: ROOT_OP,
      method: "GET",
      data: {
        "delete-link": 1,
        id: id,
      },
      success: (e) => {
        if (e === "success") window.location.href = window.location.href;
      },
    });
  }
};
const handleDeleteChild = (id) => {
  let k = confirm("Do you want to Delete! click ok to delete.");
  if (k) {
    $.ajax({
      url: ROOT_OP,
      method: "GET",
      data: {
        "delete-child": 1,
        id: id,
      },
      success: (e) => {
        if (e === "success") window.location.href = window.location.href;
      },
    });
  }
};
const handleAddChild = (element) => {
  const y = element.innerHTML;
  element.innerHTML = "Loading...";
  let i = document.querySelector("#fileID").value;
  let f = document.querySelector("#filename").value;
  if (f === "") {
    alert("Enter filename.");
    return;
  }
  $.ajax({
    url: ROOT_OP,
    method: "GET",
    data: {
      "add-child": 1,
      filename: f,
      id: i,
    },
    success: (e) => {
      element.innerHTML = y;
      console.log(e);
      if (e === "success") window.location.href = window.location.href;
      if (e === "exist")
        alert("child name already exist choose another name...");
    },
  });
};
const saveChildChanges = async (element) => {
  const y = element.innerHTML;
  element.innerHTML = "Loading...";
  let id = document.querySelector("#fileID").value;
  let renamedLink = document.querySelector("#renamed").value;
  let to = document.querySelector("#filename").value;
  let pid = document.querySelector("#pid").value;
  let parentName = document.querySelector("#parent-name").value;
  let finalLink = document.querySelector("#final-link").value;
  if (to === "") {
    alert("Enter filename.");
    return;
  }
  await $.ajax({
    url: ROOT_OP,
    method: "POST",
    async: true,
    data: {
      updateName: 1,
      to,
      id,
      pid,
      parentName,
      finalLink,
      renamedLink,
    },
    success: (e) => {
      element.innerHTML = y;
      console.log("res: ", e);
      if (e === "success") window.location.href = window.location.href;
    },
  });
};
